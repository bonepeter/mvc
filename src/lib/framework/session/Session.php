<?php

namespace lib\framework\session;

interface Session {

	public function getVar($varName);

	public function setVar($varName, $value);
}
