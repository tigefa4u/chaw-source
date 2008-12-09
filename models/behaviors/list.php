<?php

/*
 * List behavior for cakePHP
 * comments, bug reports are welcome skie AT mail DOT ru
 * @author Yevgeny Tomenko aka SkieDr
 * @version 1.0.0.7
 *
 * modifed Dec 8, 2008 [gwoo]
 */

class ListBehavior extends ModelBehavior {

	var $settings = array();

	function setup(&$model, $config = array()) {
		$this->listSetup($model, $config);
	}

	function listSetup(&$model, $config = array()) {
		$settings = am(array(
		'position_column' => 'position',
		'scope' => '',
		), $config);
		$this->settings[$model->alias] = $settings;
	}
/**
 * Before save method. Called before all saves
 *
 * Overriden to transparently manage setting the item position to the end of the list
 *
 * @param AppModel $model
 * @return boolean True to continue, false to abort the save
 */
	function beforeSave(&$model) {
		extract($this->settings[$model->alias]);
		if (empty($model->data[$model->alias][$model->primaryKey])) {
			$this->__addToListBottom($model);
		}

		return true;
	}
/**
 * Before delete method. Called before all deletes
 *
 * Will delete the current item from list and update position of all items after one
 *
 * @param AppModel $model
 * @return boolean True to continue, false to abort the delete
 */
	function beforeDelete(&$model) {
		$dataStore = $model->data;
		$model->recursive = 0;
		$model->read(null,$model->id);
		extract($this->settings[$model->alias]);
		$result = $this->removeFromList($model);
		$model->data = $dataStore;
		return $result;
	}
/**
 *  SetById method. Check is model innitialized.
 *
 *  If $id is defined read record from model with this primary key value
 *
 * @param AppModel $model
 * @param ID $id  - value of model primary key to read
 * @return boolean True if model initialized, false if no info in $model->data exists.
 * @access private
 */
	function __setById(&$model, $id=null, $checkId=true) {
		if (!isset($id)) {
			if ($checkId)
				return isset($model->data[$model->alias]['id']);
			else
				return isset($model->data[$model->alias]);
		} else {
			return $model->read(null, $id);
		}
	}
/**
 *  Set new position of selected item for model
 *
 * @param AppModel $model
 * @param int $position new position of item in list
 * @param ID $id  - value of model primary key to read
 * @access public
 */
	function insertAt(&$model,$position = 1, $id=null) {
		if (!$this->__setById($model, $id, false)) {return false;}
		return $this->__insertAtPosition($model,$position);
	}
/**
 * Swap positions with the next lower item, if one exists.
 *
 * @param AppModel $model
 * @param ID $id  - value of model primary key to read
 * @access public
 */
	function moveLower(&$model, $id=null) {
		if (!$this->__setById($model, $id)) return false;
		$lowerItem = $this->lowerItem($model);
		if ($lowerItem==null) return;

	  # todo: add transaction
		$currData=$model->data;
		$model->set($lowerItem);
		$this->_decrementPosition($model);
		$model->set($currData);
		return $this->_incrementPosition($model);
	  # todo: add transaction
	}
/**
 * Swap positions with the next higher item, if one exists.
 *
 * @param AppModel $model
 * @param ID $id  - value of model primary key to read
 * @access public
 */
	function moveHigher (&$model, $id=null) {
		if (!$this->__setById($model, $id)) return false;
		$higherItem = $this->higherItem($model);
		if ($higherItem==null) return;

		# todo: add transaction
		$currData=$model->data;
		$model->set($higherItem);
		$this->_incrementPosition($model);
		$model->set($currData);
		return $this->_decrementPosition($model);
		# todo: add transaction
	}
/**
* Move to the bottom of the list. If the item is already in the list, the items below it have their
* position adjusted accordingly.
 *
 * @param AppModel $model
 * @param ID $id  - value of model primary key to read
 * @access public
 */
	function moveToBottom (&$model, $id=null) {
		if (!$this->__setById($model, $id)) return false;
		if (!$this->isInList($model)) return;
		# todo: add transaction
		$this->__decrementPositionsOnLowerItems($model);
		return $this->__assumeBottomPosition($model);
		# todo: add transaction
	}
/**
 * Move to the top of the list. If the item is already in the list, the items above it have their
 * position adjusted accordingly.
 *
 * @param AppModel $model
 * @param ID $id  - value of model primary key to read
 * @access public
 */
	function moveToTop (&$model, $id=null) {
	  if (!$this->__setById($model, $id)) return false;
	  if (!$this->isInList($model)) return;
	  # todo: add transaction
		$this->__incrementPositionsOnHigherItems($model);
		return $this->__assumeTopPosition($model);
	  # todo: add transaction
	}

	function removeFromList(&$model, $id=null) {
		if (!$this->__setById($model, $id)) return false;
		if ($this->isInList($model)) return $this->__decrementPositionsOnLowerItems($model);
	}
/**
	 * Increase the position of this item without adjusting the rest of the list.
 *
 * @param AppModel $model
 * @access private
 */
	function _incrementPosition(&$model) {
	  if (!$this->isInList($model)) return;
	  extract($this->settings[$model->alias]);
	  $model->data[$model->alias][$position_column]++;
	  return $model->save();
	}
/**
	 * Decrease the position of this item without adjusting the rest of the list.
 *
 * @param AppModel $model
 * @access private
 */
	function _decrementPosition(&$model) {
		if (!$this->isInList($model)) return;
		extract($this->settings[$model->alias]);
		$model->data[$model->alias][$position_column]--;
		return $model->save();
	}
/**
 * Return true if this object is the first in the list.
	 *
 * @param AppModel $model
 * @access public
 */
	function isFirst(&$model, $id=null) {
	  if (!$this->__setById($model, $id)) return false;
		extract($this->settings[$model->alias]);
		if (!$this->isInList($model)) return false;
		return $model->data[$model->alias][$position_column]==1;
	}
/**
 * Return true if this object is the last in the list.
	 *
 * @param AppModel $model
 * @access public
 */
	function isLast(&$model, $id=null) {
		if (!$this->__setById($model, $id)) return false;
		extract($this->settings[$model->alias]);
		if (!$this->isInList($model)) return false;
		return $model->data[$model->alias][$position_column]==$this->__bottomPositionInList($model);
	}
/**
 * Return the next higher item in the list.
	 *
 * @param AppModel $model
 * @access public
 */
	function higherItem(&$model, $id=null) {
	  if (!$this->__setById($model, $id)) return false;
		extract($this->settings[$model->alias]);
		if (!$this->isInList($model)) return null;
		$model->recursive = 0;
		return $model->find(array($this->__scopeCondition($model), $position_column => $model->data[$model->alias][$position_column]-1));
	}
/**
 * Return the next lower item in the list.
	 *
 * @param AppModel $model
 * @access public
 */
	function lowerItem(&$model, $id=null) {
		if (!$this->__setById($model, $id)) return false;
		extract($this->settings[$model->alias]);
		if (!$this->isInList($model)) return null;
		$model->recursive = 0;
		return $model->find(array($this->__scopeCondition($model), $position_column => $model->data[$model->alias][$position_column]+1));
	}
/**
 * Return true if item in the list.
	 *
 * @param AppModel $model
 * @access public
 */
	function isInList(&$model) {
		extract($this->settings[$model->alias]);
		if (empty($model->data[$model->alias][$position_column])) return false;
		return !($model->data[$model->alias][$position_column] == null);
	}

//private
    function __scopeCondition(&$model) {
		extract($this->settings[$model->alias]);
		$scopes = array();
		if (is_string($scope)) {
			if ($scope=='') return $scopes;
			if (substr($scope, -3) != '_id') {
				$scope .= '_id';
			}
			$scopes[$model->alias . '.' . $scope] = $model->data[$model->alias][$scope]; //$model->alias.'.'.
		} elseif (is_array($scope)) {
			foreach ($scope as $scopeEl) {
				if (substr($scopeEl, -3)=='_id') {
					$scopeEl .= '_id';
				}
				$scopes[$model->alias . '.' . $scopeEl] = $model->data[$model->alias][$scopeEl]; //$model->alias.'.'.
			}
		}
		//if (count($scopes)==0) $scopes[]="1=1";
		return $scopes;
	}


	function __addToListTop(&$model) {
		return $this->__incrementPositionsOnAllItems($model);
	}

	function __addToListBottom(&$model) {
		extract($this->settings[$model->alias]);
		$model->data[$model->alias][$position_column] = $this->__bottomPositionInList($model) + 1;
	}

	function __bottomPositionInList(&$model,$except = null) {
		extract($this->settings[$model->alias]);
		$item = $this->__bottomItem($model,$except);

		if ($item) {
			return $item[$model->alias][$position_column];
		}
		else {
			return 0;
		}
	}

	function __bottomItem(&$model,$except=null) {
		extract($this->settings[$model->alias]);
		$conditions = $this->__scopeCondition($model);
		if (is_string($conditions)) $conditions=array($conditions);
		if ($except!=null) $conditions = am ($conditions, array($model->alias.'.'.$model->primaryKey => "!= ".$except[$model->alias][$model->primaryKey]));
		$model->recursive = 0;
		return $model->find($conditions, null, array($model->alias .'.' . $position_column => 'DESC'));
	}

	function __assumeBottomPosition(&$model) {
		extract($this->settings[$model->alias]);
		$model->data[$model->alias][$position_column] = $this->__bottomPositionInList($model,$model->data)+1;
		return $model->save();
	}


	function __assumeTopPosition(&$model) {
		extract($this->settings[$model->alias]);
		$model->data[$model->alias][$position_column] = 1;
		return $model->save();
	}

	# This has the effect of moving all the higher items up one.
	function __decrementPositionsOnHigherItems (&$model, $position) {
		extract($this->settings[$model->alias]);
		return $model->updateAll(array($model->alias .'.' . $position_column => $model->alias .'.' . $position_column .'-1'), array($this->__scopeCondition($model), $model->alias .'.' . $position_column => "<= $position"));
    }

	# This has the effect of moving all the lower items up one.
	function __decrementPositionsOnLowerItems(&$model) {
		if (!$this->isInList($model)) return;
		extract($this->settings[$model->alias]);
		return $model->updateAll(array($model->alias .'.' . $position_column => $model->alias .'.' . $position_column .'-1'), array($this->__scopeCondition($model), $model->alias .'.' . $position_column => "> ".$model->data[$model->alias][$position_column]));
    }

	# This has the effect of moving all the higher items down one.
	function __incrementPositionsOnHigherItems (&$model) {
		if (!$this->isInList($model)) return;
		extract($this->settings[$model->alias]);
		return $model->updateAll(array($model->alias .'.' . $position_column => $model->alias .'.' . $position_column . '+1'), array($this->__scopeCondition($model), $model->alias .'.' . $position_column => "< ".$model->data[$model->alias][$position_column]));
    }

    # This has the effect of moving all the lower items down one.
	function __incrementPositionsOnLowerItems (&$model, $position) {
		extract($this->settings[$model->alias]);
		return $model->updateAll(array($model->alias .'.' . $position_column => $model->alias .'.' . $position_column .'+1'), array($this->__scopeCondition($model), $model->alias .'.' . $position_column => ">= $position"));
    }

	function __incrementPositionsOnAllItems (&$model) {
		extract($this->settings[$model->alias]);
		return $model->updateAll(array($model->alias .'.' . $position_column => $model->data[$model->alias][$position_column]+1), array($this->__scopeCondition($model)));
    }

	function __insertAtPosition(&$model, $position) {
		extract($this->settings[$model->alias]);
		$model->save();
		$model->recursive = 0;
		$model->findById($this->id);
		$this->removeFromList($model);
		$result=$this->__incrementPositionsOnLowerItems($model, $position);
		if ($position<=$this->__bottomPositionInList($model)) {
			$model->data[$model->alias][$position_column]=$position;
			$result=$model->save();
		}
		return $result;
	}
}
?>