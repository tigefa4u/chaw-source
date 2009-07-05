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
class UpgradeTwoTask extends ChawUpgradeShell {

	function commits() {
		$this->Timeline->cacheSources = false;
		$this->Commit->cacheSources = false;

		$this->Timeline = ClassRegistry::init('Timeline');
		$this->Timeline->setSource('timeline');

		$this->Commit = ClassRegistry::init('Commit');
		$this->Commit->setSource('commits');

		$this->Commit->addToTimeline = false;

		if ($this->_updateSchema('commits', array('new' => true)) == false) {
			return false;
		}

		if ($this->_updateSchema('timeline', array('new' => true)) == false) {
			return false;
		}

		//$conditions = array('Project.repo_type' => 'git');

		$conditions = array();
		$count = $this->Timeline->find('count', compact('conditions'));

		for ($i = 1; $i * 100 < $count; $i++) {
			$this->Timeline->setSource('timeline');
			$this->Commit->setSource('commits');
			$events = $this->Timeline->find('all', array(
				'conditions' => $conditions,
				'order' => array(
					'Timeline.id' => 'ASC',
				),
				'limit' => '100',
				'page' => $i
			));

			$this->Timeline->setSource('new_timeline');
			$this->Commit->setSource('new_commits');

			$previous = null;
			$batch = array();

			foreach ($events as $key => $event) {
				$event['Timeline']['event'] = 'added';

				if ('Commit' == $event['Timeline']['model']) {
					if (!empty($previous) && $previous['Timeline']['model'] == 'Commit' && $previous['Timeline']['created'] == $event['Timeline']['created']) {
						$batch[] = $event;
						$previous = $event;
						continue;
					}
					if (!empty($events[$key+1]) && empty($batch)) {
						$next = $events[$key+1];
						if ($next['Timeline']['model'] == 'Commit' && $next['Timeline']['created'] == $event['Timeline']['created']) {
							$batch[] = $event;
							$previous = $event;
							continue;
						}
					}
				}

				$previous = $event;

				if (!empty($batch) && $batch > 1) {
					$first = $batch[0];
					$last = end($batch);
					$commit = $last['Commit'];

					$last['Timeline']['user_id'] = $last['Commit']['user_id'];
					$last['Timeline']['event'] = 'pushed';

					if ($commit['revision'] !== $first['Commit']['revision']) {
						$last['Timeline']['data'] = count($batch);
						$commit['changes'] = $first['Commit']['revision'] . '..' . $commit['revision'];
					}

					$this->Commit->create($commit);
					if ($this->Commit->save()) {
						$this->out("Commit {$commit['id']} upgraded");
					} else {
						$this->out("ERROR: Batch Commit {$commit['id']} NOT upgraded");
					}

					$this->Timeline->create($last['Timeline']);
					if ($this->Timeline->save()) {
						$this->out("Timeline {$last['Timeline']['id']} upgraded");
					} else {
						$this->out("Timeline {$last['Timeline']['id']} NOT upgraded");
					}
					$batch = array();
					continue;
				}

				if ('Wiki' == $event['Timeline']['model']) {
					$event['Timeline']['event'] = 'updated';
				}

				if ('Commit' == $event['Timeline']['model']) {

					$commit = $event['Commit'];

					$event['Timeline']['event'] = 'pushed';
					$event['Timeline']['data'] = 0;
					$event['Timeline']['user_id']  = $commit['user_id'];

					$this->Commit->create($commit);
					if ($this->Commit->save()) {
						$this->out("Commit {$commit['id']} upgraded");
					} else {
						$this->out("ERROR: Commit {$commit['id']} NOT upgraded");
					}
				}

				$this->Timeline->create($event['Timeline']);
				if ($this->Timeline->save()) {
					$this->out("Timeline {$event['Timeline']['id']} upgraded");
				} else {
					$this->out("Timeline {$event['Timeline']['id']} NOT upgraded");
				}

				usleep(25);
			}
		}
	}

}
