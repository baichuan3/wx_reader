<?php
class RequestValidator {

	private $filter = 'Filter';
    private $validator = 'Validator';
	private $seperator = '-';

	public function __construct() {}

	public function getQuery($id, $default = null, $filterStr = '', $validator = '') {
		$data = Yaf_Application::app()->getDispatcher()->getRequest()->getQuery($id , $default);
		//if ($data == $default) return $default;

		if ($filterStr) {
            if (strpos($filterStr, '|') !== false) {
                $farr = explode('|', $filterStr);
            } else {
                $farr = array($filterStr);
            }

            $reflection = new ReflectionClass($this->filter);
            foreach($farr as $filter) {
                $method = null;
                $args = null;
                if (strpos($filter, ':') !== false) {
                    list($method, $args) = explode(':', $filter);
                } else {
                    $method = $filter;
                }

                try {
					$tmp = array();
                    $methodInfo = $reflection->getMethod('_' . $method);
                    $tmp[] = $data;
                    $tmp = array_merge($tmp, explode($this->seperator, $args));
                    $data = $methodInfo->invokeArgs($reflection->newInstance(), $tmp);
                } catch(ReflectionException $fe) {
					//qLogInfo("CloudSearch.QSSWEB.NEWS.reflection_failed","class:". $this->filter ."|method:" . $method . '|err:' . $fe->getMessage());
                }
            }
		}

		$isValidate = true;
		if ($validator !== '') {
        	$validatorInstance = new $this->validator;
        	$isValidate = $validatorInstance->$validator($data);
		}

        return $isValidate ? $data : false;
	}
	
	public function getPost($id, $default = null, $filterStr = '', $validator = '') {
		$data = yaf_Application::app()->getDispatcher()->getRequest()->getPost($id , $default);
		if (empty($data)) return $default;

		if ($filterStr) {
            if (strpos($filterStr, '|') !== false) {
                $farr = explode('|', $filterStr);
            } else {
                $farr = array($filterStr);
            }

            $reflection = new ReflectionClass($this->filter);
            foreach($farr as $filter) {
                $method = null;
                $args = null;
                if (strpos($filter, ':') !== false) {
                    list($method, $args) = explode(':', $filter);
                } else {
                    $method = $filter;
                }

                try {
                    $methodInfo = $reflection->getMethod('_' . $method);
                    $args[] = $data;
                    $args += explode($this->seperator, $args);
                    $data = $methodInfo->invokeArgs($this, $args);
                } catch (ReflectionException $fe) {
					//qLogInfo("CloudSearch.QSSWEB.NEWS.reflection_failed","class:". $this->filter ."|method:" . $method . '|err:' . $fe->getMessage());
                }
            }
		}

		$isValidate = true;
		if ($validator !== '') {
        	$validatorInstance = new $this->validator;
        	$isValidate = $validatorInstance->$validator($data);
		}

        return $isValidate ? $data : false;
	}

	public function setSeperator($char) {
		if (!empty($char)) $this->seperator = $char;
	}
}
