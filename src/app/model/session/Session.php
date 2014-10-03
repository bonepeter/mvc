<?php

namespace app\model\session;

interface Session {

	public function getVar($varName);

	public function setVar($varName, $value);
}
