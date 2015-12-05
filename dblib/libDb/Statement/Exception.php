<?php
namespace libDb\Statement;
class Exception extends \libDb\Exception
{
    /**
     * Check if this general exception has a specific database driver specific exception nested inside.
     *
     * @return bool
     */
    public function hasChainedException()
    {
        return ($this->getPrevious() !== null);
    }

    /**
     * @return Exception|null
     */
    public function getChainedException()
    {
        return $this->getPrevious();
    }
}
