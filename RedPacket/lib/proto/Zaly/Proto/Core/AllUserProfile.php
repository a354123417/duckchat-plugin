<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: core/user.proto

namespace Zaly\Proto\Core;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>core.AllUserProfile</code>
 */
class AllUserProfile extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.core.PublicUserProfile public = 1;</code>
     */
    private $public = null;
    /**
     * Generated from protobuf field <code>int64 timeReg = 2;</code>
     */
    private $timeReg = 0;

    public function __construct() {
        \GPBMetadata\Core\User::initOnce();
        parent::__construct();
    }

    /**
     * Generated from protobuf field <code>.core.PublicUserProfile public = 1;</code>
     * @return \Zaly\Proto\Core\PublicUserProfile
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Generated from protobuf field <code>.core.PublicUserProfile public = 1;</code>
     * @param \Zaly\Proto\Core\PublicUserProfile $var
     * @return $this
     */
    public function setPublic($var)
    {
        GPBUtil::checkMessage($var, \Zaly\Proto\Core\PublicUserProfile::class);
        $this->public = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int64 timeReg = 2;</code>
     * @return int|string
     */
    public function getTimeReg()
    {
        return $this->timeReg;
    }

    /**
     * Generated from protobuf field <code>int64 timeReg = 2;</code>
     * @param int|string $var
     * @return $this
     */
    public function setTimeReg($var)
    {
        GPBUtil::checkInt64($var);
        $this->timeReg = $var;

        return $this;
    }

}

