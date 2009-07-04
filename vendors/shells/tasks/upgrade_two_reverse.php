<?php
/**
 * Short description
 *
 * Long description
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Redistributions not permitted
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw
 * @subpackage		chaw.vendors.shells
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
class UpgradeTwoReverseTask extends ChawUpgradeShell {

	function commits() {
		$this->Timeline->cacheSources = false;
		$this->Commit->cacheSources = false;

		$this->Timeline = ClassRegistry::init('Timeline');
		$this->Timeline->setSource('timeline');

		$this->Timeline->bindModel(array('belongsTo' => array(
			'BranchCommit' => array(
				'foreignKey' => 'foreign_key',
				'conditions' => array('Timeline.model = \'BranchCommit\'')
			)
		)));
		
		$this->Branch = ClassRegistry::init('Branch');
		
	
		$this->Commit = ClassRegistry::init('Commit');
		$this->Commit->setSource('commits');

		$this->Commit->addToTimeline = false;

		$events = $this->Timeline->find('all', array(
			'order' => array(
				'Timeline.created' => 'ASC',
				'Timeline.id' => 'ASC',
			)
		));

		if ($this->_updateSchema('commits', array('new' => true)) == false) {
			return false;
		}

		if ($this->_updateSchema('timeline', array('new' => true)) == false) {
			return false;
		}

		$this->Timeline->setSource('new_timeline');
		$this->Commit->setSource('new_commits');

		$previous = null;
		$batch = array();

		foreach ($events as $i => $event) {
			$this->Commit->setSource('new_commits');

			$event['Timeline']['event'] = 'added';

			if ('BranchCommit' == $event['Timeline']['model']) {

				if (!empty($events[$i+1]) && empty($batch)) {
					$next = $events[$i+1];
					if ($next['Timeline']['model'] == 'BranchCommit' && $next['Timeline']['created'] == $event['Timeline']['created']) {
						$batch[] = $event;
						$this->out("Timeline {$event['Timeline']['id']} batched");
						$previous = $event;
						continue;
					}
				}

				if (!empty($batch) && !empty($previous) && $previous['Timeline']['model'] == 'BranchCommit' && $previous['Timeline']['created'] == $event['Timeline']['created']) {
					$batch[] = $event;
					$this->out("Timeline {$event['Timeline']['id']} batched");
					$previous = $event;
					continue;
				}

			}

			$previous = $event;

			if (!empty($batch)) {
				$count = count($batch);
				$first = $batch[0];
				$event = end($batch);

				$this->Commit->setSource('commits');
				$first = $this->Commit->findById($first['BranchCommit']['commit_id']);
				$commit = $this->Commit->findById($event['BranchCommit']['commit_id']);
				$this->Commit->setSource('new_commits');
				
				$branch = $this->Branch->findById($event['BranchCommit']['branch_id']);
				
				
				if (!empty($commit)) {
					$event['Timeline']['user_id']  = $commit['Commit']['user_id'];
					$event['Timeline']['model'] = 'Commit';
					$event['Timeline']['foreign_key'] = $commit['Commit']['id'];
					$event['Timeline']['event'] = 'pushed';
					$event['Timeline']['data'] = $count;

					$commit['Commit']['branch'] = $branch['Branch']['name'];
					$commit['Commit']['changes'] = $first['Commit']['revision'] . '..' . $commit['Commit']['revision'];

					$this->Commit->create($commit);
					if ($this->Commit->save()) {
						$this->out("Commit {$commit['Commit']['id']} upgraded");
					} else {
						$this->out("ERROR: Batch Commit {$commit['Commit']['id']} NOT upgraded");
					}

					$this->Timeline->create($event['Timeline']);
					if ($this->Timeline->save()) {
						$this->out("Timeline {$event['Timeline']['id']} saved");
					}
				}
				$previous = null;
				$this->out("Timeline {$event['Timeline']['id']} upgraded");
				$batch = array();
				continue;
			}

			if ('Wiki' == $event['Timeline']['model']) {
				$event['Timeline']['event'] = 'updated';
			}

			if ('BranchCommit' == $event['Timeline']['model']) {
				$this->Commit->setSource('commits');
				$commit = $this->Commit->findById($event['BranchCommit']['commit_id']);
				$this->Commit->setSource('new_commits');

				if (!empty($commit)) {
					$event['Timeline']['user_id']  = $commit['Commit']['user_id'];
					$event['Timeline']['model'] = 'Commit';
					$event['Timeline']['foreign_key'] = $commit['Commit']['id'];
					$event['Timeline']['event'] = 'pushed';
					$event['Timeline']['data'] = 0;

					$this->Commit->create($commit);
					if ($this->Commit->save()) {
						$this->out("Commit {$commit['Commit']['id']} upgraded");
					} else {
						$this->out("ERROR: Commit {$commit['Commit']['id']} NOT upgraded");
					}
				}
			}

			$this->Timeline->create($event['Timeline']);
			if ($this->Timeline->save()) {
				$this->out("Timeline {$event['Timeline']['id']} upgraded");
			} else {
				$this->out("Timeline {$event['Timeline']['id']} NOT upgraded");
			}
			$batch = array();
			usleep(25);
		}
	}
}
