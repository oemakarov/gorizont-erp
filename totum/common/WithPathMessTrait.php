<?php


namespace totum\common;

/*
 * for Exceptions
 * */
trait WithPathMessTrait
{
    protected $pathMess;

    /**
     * @return mixed
     */
    public function getPathMess()
    {
        return $this->pathMess;
    }

    public function addPath($path)
    {
        if (empty($this->pathMess)) {
            $this->pathMess = $path;
        } else {
            $this->pathMess = $this->getPathMess() . '; ' . $path;
        }
    }
}
