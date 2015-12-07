<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 07.12.15
 * Time: 12:37
 */

/**
 * Class SharedMemory
 */

class SharedMemory {

    private $__size = 1048576; // 1mb by default
    private $__path = '.'; // Shared memory token generator path
    private $__proj_id = 'm';

    // Internal vars
    private $__key = NULL;
    private $__shm = NULL;
    private $__mutex = NULL;

    public function __construct($size, $path = '.') {
        if (0 < $size = intval($size)) {
            $this->__size = $size;
        }
        $this->__path = $path;
        $this->attach();
    }

    /**
     * Create shared memory block
     */
    private function attach() {
        $this->__key = ftok($this->__path, $this->__proj_id);
        $this->__shm = shm_attach($this->__key, $this->__size); //allocate shared memory
        $this->__mutex = sem_get($this->__key, 1); //create mutex with same key
    }

    /**
     * Set shared memory var
     * @param $var
     */
    public function set($var) {
        sem_acquire($this->__mutex); //block until released
        shm_put_var($this->__shm, $this->__key, $var); //store var
        sem_release($this->__mutex); //release mutex
    }

    /**
     * Read shared memory var
     * @return mixed|null
     */
    public function get() {
        sem_acquire($this->__mutex); //block until released
        $var = @shm_get_var($this->__shm, $this->__key); //read var
        sem_release($this->__mutex); //release mutex
        return !empty($var) ? $var : NULL;
    }

    /**
     * Delete shared memory var
     */
    public function del() {
        sem_acquire($this->__mutex); //block until released
        @shm_remove_var($this->__shm, $this->__key);
        sem_release($this->__mutex); //release mutex
    }

    /**
     * Force release
     */
    public function destroy() {
        sem_remove($this->__mutex);
        shm_remove ($this->__shm);
    }

    /**
     * unserialize shared memory
     */
    public function __wakeup() {
        $this->attach();
    }
}