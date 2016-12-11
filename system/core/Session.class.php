<?php
/**
 * Maes Jerome
 * Session.class.php, created at May 26, 2015
 *
 */
namespace \system\core;

class Session{
	

	protected $started = false;

	protected $closed = false;
	
	private $storage = array();
	
	
	
	
	public function start(){
		if ($this->started) {
			return true;
		}
	
		if (PHP_VERSION_ID >= 50400 && \PHP_SESSION_ACTIVE === session_status()) {
			throw new \RuntimeException('Failed to start the session: already started by PHP.');
		}
	
		if (PHP_VERSION_ID < 50400 && !$this->closed && isset($_SESSION) && session_id()) {
			// not 100% fool-proof, but is the most reliable way to determine if a session is active in PHP 5.3
			throw new \RuntimeException('Failed to start the session: already started by PHP ($_SESSION is set).');
		}
	
		// ok to try and start the session
		if (!session_start()) {
			throw new \RuntimeException('Failed to start the session');
		}
	/*
		$this->loadSession();
		if (!$this->saveHandler->isWrapper() && !$this->saveHandler->isSessionHandlerInterface()) {
			// This condition matches only PHP 5.3 with internal save handlers
			$this->saveHandler->setActive(true);
		}
	*/
		return true;
	}
	
	
	public function regenerate($destroy = false, $lifetime = null){
		/*
		if (null !== $lifetime) {
			ini_set('session.cookie_lifetime', $lifetime);
		}
		*/
		return session_regenerate_id($destroy);
	}
	
	
	public function destroy(){
		return session_destroy();
	}
	
	
	public function authenticate($login, $password){
		
	}
	

    public function has($name){
    	return array_key_exists($name, $this->storage);
    }

    public function get($name, $default = null){
    	if($this->has($name)){
    		return $this->storage[$name];
    	}
        return $default;
    }

    public function set($name, $value){
        $this->storage[$name] = $value;
    }
	

	
	public function isStarted(){
		return $this->started;
	}
}