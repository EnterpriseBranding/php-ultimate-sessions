<?php

namespace MikeBrant\UltimateSessions;

/**
 * Class UltimateSessionManagerMetadata
 *
 * This class represents simple structured storage object for session metadata
 * which UltimateSessionManager class will set into $_SESSION superglobal for
 * session management.  This class is intentionally left mutable as the
 * UltimateSessionManager class truly owns this data. This class is provided
 * really just to provide separation of metadata storage model from consuming
 * session manager as well as convenient instantiation for setting this
 * structure in a clean state upon generation of new session ID's.
 *
 * @package MikeBrant\UltimateSessions
 */
class UltimateSessionManagerMetadata implements \JsonSerializable
{
    /**
     * @var int Timestamp value as generated from \time() function.
     */
    public $instantiatedAt;

    /**
     * @var int Timestamp value representing time after which the session
     * should be forced to change ID's.
     */
    public $regenerateIdAt;

    /**
     * @var int Counter for session_start() calls for current session ID to
     * be used by UltimateSessionManager cass as means to force session ID
     * regeneration after X number of session start events on current session
     * ID.
     */
    public $sessionStartCount;

    /**
     * @var bool Boolean representing whether the session is considered
     * active.  When set to false, this indicates that the session ID's in
     * which this metadata lives has been replaced by a new session ID due to
     * a session ID regeneration event.
     */
    public $isActive;

    /**
     * @var int Value indicating timestamp above which this session, which
     * has previously been subject to an ID regeneration should no longer be
     * forwarded to new session ID and should be considered fully expired and
     * subject to data being fully destroyed for security reasons.
     */
    public $expireDataAt;

    /**
     * @var string Value indicating a new session ID that has been generated
     * to replace the session ID of currently accessed session ID. If
     * timestamp of this access is less than the $expireDataAt timestamp,
     * then this session should be forwarded to new session ID.
     */
    public $forwardToSessionId;

    /**
     * @var string Session fingerprint hash as generated by
     * UltimateSessionManager class.
     */
    public $fingerprint;

    /**
     * UltimateSessionManagerMetadata constructor.
     */
    public function __construct()
    {
        $this->instantiatedAt = time();
        $this->sessionStartCount = 1;
        $this->isActive = true;
        $this->expireDataAt = 0;
        $this->forwardToSessionId = '';
    }

    /**
     * Method implementing JsonSerializable interface to allow object to be
     * serialized to JSON.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}