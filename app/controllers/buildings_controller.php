<?php
class BuildingsController extends AppController {

	var $name = 'Buildings';

	function admin_index() {
		$this->Building->recursive = 0;
		$this->set('buildings', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid building', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('building', $this->Building->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Building->create();
			if ($this->Building->save($this->data)) {
				$this->Session->setFlash(__('The building has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The building could not be saved. Please, try again.', true));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid building', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Building->save($this->data)) {
				$this->Session->setFlash(__('The building has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The building could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Building->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for building', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Building->delete($id)) {
			$this->Session->setFlash(__('Building deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Building was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>