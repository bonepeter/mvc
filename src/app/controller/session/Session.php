<?php

namespace app\controller\session;

interface Session {

	public function getVar($varName);

	public function setVar($varName, $value);
}
