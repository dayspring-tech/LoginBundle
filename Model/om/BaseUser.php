<?php

namespace Dayspring\LoginBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Dayspring\LoginBundle\Model\RoleUser;
use Dayspring\LoginBundle\Model\RoleUserQuery;
use Dayspring\LoginBundle\Model\SecurityRole;
use Dayspring\LoginBundle\Model\SecurityRoleQuery;
use Dayspring\LoginBundle\Model\User;
use Dayspring\LoginBundle\Model\UserPeer;
use Dayspring\LoginBundle\Model\UserQuery;
use GOEDCSD\CommonBundle\Model\ArticleOpinion;
use GOEDCSD\CommonBundle\Model\ArticleOpinionQuery;
use GOEDCSD\CommonBundle\Model\AuditLog;
use GOEDCSD\CommonBundle\Model\AuditLogQuery;
use GOEDCSD\CommonBundle\Model\BaselineStateTableMetadata;
use GOEDCSD\CommonBundle\Model\BaselineStateTableMetadataQuery;
use GOEDCSD\CommonBundle\Model\FullReviewOpinion;
use GOEDCSD\CommonBundle\Model\FullReviewOpinionApproval;
use GOEDCSD\CommonBundle\Model\FullReviewOpinionApprovalQuery;
use GOEDCSD\CommonBundle\Model\FullReviewOpinionLog;
use GOEDCSD\CommonBundle\Model\FullReviewOpinionLogQuery;
use GOEDCSD\CommonBundle\Model\FullReviewOpinionQuery;
use GOEDCSD\CommonBundle\Model\GroupOpinion;
use GOEDCSD\CommonBundle\Model\GroupOpinionQuery;
use GOEDCSD\CommonBundle\Model\InitialReviewOpinion;
use GOEDCSD\CommonBundle\Model\InitialReviewOpinionLog;
use GOEDCSD\CommonBundle\Model\InitialReviewOpinionLogQuery;
use GOEDCSD\CommonBundle\Model\InitialReviewOpinionQuery;
use GOEDCSD\CommonBundle\Model\MeasurementOpinion;
use GOEDCSD\CommonBundle\Model\MeasurementOpinionQuery;
use GOEDCSD\CommonBundle\Model\PMQuery;
use GOEDCSD\CommonBundle\Model\PMQueryQuery;
use GOEDCSD\CommonBundle\Model\TimepointOpinion;
use GOEDCSD\CommonBundle\Model\TimepointOpinionQuery;
use GOEDCSD\CommonBundle\Model\TreatmentOpinion;
use GOEDCSD\CommonBundle\Model\TreatmentOpinionQuery;
use GOEDCSD\CommonBundle\Model\UserDetails;
use GOEDCSD\CommonBundle\Model\UserDetailsQuery;
use GOEDCSD\CommonBundle\Model\om\BaseMeasurementOpinion;

abstract class BaseUser extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Dayspring\\LoginBundle\\Model\\UserPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        UserPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the email field.
     * @var        string
     */
    protected $email;

    /**
     * The value for the password field.
     * @var        string
     */
    protected $password;

    /**
     * The value for the salt field.
     * @var        string
     */
    protected $salt;

    /**
     * The value for the reset_token field.
     * @var        string
     */
    protected $reset_token;

    /**
     * The value for the reset_token_expire field.
     * @var        string
     */
    protected $reset_token_expire;

    /**
     * The value for the created_date field.
     * @var        string
     */
    protected $created_date;

    /**
     * The value for the last_login_date field.
     * @var        string
     */
    protected $last_login_date;

    /**
     * The value for the is_active field.
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $is_active;

    /**
     * @var        PropelObjectCollection|RoleUser[] Collection to store aggregation of RoleUser objects.
     */
    protected $collRoleUsers;
    protected $collRoleUsersPartial;

    /**
     * @var        PropelObjectCollection|ArticleOpinion[] Collection to store aggregation of ArticleOpinion objects.
     */
    protected $collArticleOpinions;
    protected $collArticleOpinionsPartial;

    /**
     * @var        PropelObjectCollection|AuditLog[] Collection to store aggregation of AuditLog objects.
     */
    protected $collAuditLogs;
    protected $collAuditLogsPartial;

    /**
     * @var        PropelObjectCollection|BaselineStateTableMetadata[] Collection to store aggregation of BaselineStateTableMetadata objects.
     */
    protected $collBaselineStateTableMetadatas;
    protected $collBaselineStateTableMetadatasPartial;

    /**
     * @var        PropelObjectCollection|FullReviewOpinion[] Collection to store aggregation of FullReviewOpinion objects.
     */
    protected $collFullReviewOpinions;
    protected $collFullReviewOpinionsPartial;

    /**
     * @var        PropelObjectCollection|FullReviewOpinionLog[] Collection to store aggregation of FullReviewOpinionLog objects.
     */
    protected $collFullReviewOpinionLogsRelatedByUserIdFrom;
    protected $collFullReviewOpinionLogsRelatedByUserIdFromPartial;

    /**
     * @var        PropelObjectCollection|FullReviewOpinionLog[] Collection to store aggregation of FullReviewOpinionLog objects.
     */
    protected $collFullReviewOpinionLogsRelatedByUserIdTo;
    protected $collFullReviewOpinionLogsRelatedByUserIdToPartial;

    /**
     * @var        PropelObjectCollection|FullReviewOpinionApproval[] Collection to store aggregation of FullReviewOpinionApproval objects.
     */
    protected $collFullReviewOpinionApprovals;
    protected $collFullReviewOpinionApprovalsPartial;

    /**
     * @var        PropelObjectCollection|GroupOpinion[] Collection to store aggregation of GroupOpinion objects.
     */
    protected $collGroupOpinions;
    protected $collGroupOpinionsPartial;

    /**
     * @var        PropelObjectCollection|InitialReviewOpinion[] Collection to store aggregation of InitialReviewOpinion objects.
     */
    protected $collInitialReviewOpinions;
    protected $collInitialReviewOpinionsPartial;

    /**
     * @var        PropelObjectCollection|InitialReviewOpinionLog[] Collection to store aggregation of InitialReviewOpinionLog objects.
     */
    protected $collInitialReviewOpinionLogsRelatedByUserIdFrom;
    protected $collInitialReviewOpinionLogsRelatedByUserIdFromPartial;

    /**
     * @var        PropelObjectCollection|InitialReviewOpinionLog[] Collection to store aggregation of InitialReviewOpinionLog objects.
     */
    protected $collInitialReviewOpinionLogsRelatedByUserIdTo;
    protected $collInitialReviewOpinionLogsRelatedByUserIdToPartial;

    /**
     * @var        PropelObjectCollection|MeasurementOpinion[] Collection to store aggregation of MeasurementOpinion objects.
     */
    protected $collMeasurementOpinions;
    protected $collMeasurementOpinionsPartial;

    /**
     * @var        PropelObjectCollection|PMQuery[] Collection to store aggregation of PMQuery objects.
     */
    protected $collPMQueriesRelatedByScientistId;
    protected $collPMQueriesRelatedByScientistIdPartial;

    /**
     * @var        PropelObjectCollection|PMQuery[] Collection to store aggregation of PMQuery objects.
     */
    protected $collPMQueriesRelatedByDataEntry1Id;
    protected $collPMQueriesRelatedByDataEntry1IdPartial;

    /**
     * @var        PropelObjectCollection|PMQuery[] Collection to store aggregation of PMQuery objects.
     */
    protected $collPMQueriesRelatedByDataEntry2Id;
    protected $collPMQueriesRelatedByDataEntry2IdPartial;

    /**
     * @var        PropelObjectCollection|TreatmentOpinion[] Collection to store aggregation of TreatmentOpinion objects.
     */
    protected $collTreatmentOpinions;
    protected $collTreatmentOpinionsPartial;

    /**
     * @var        PropelObjectCollection|TimepointOpinion[] Collection to store aggregation of TimepointOpinion objects.
     */
    protected $collTimepointOpinions;
    protected $collTimepointOpinionsPartial;

    /**
     * @var        UserDetails one-to-one related UserDetails object
     */
    protected $singleUserDetails;

    /**
     * @var        PropelObjectCollection|SecurityRole[] Collection to store aggregation of SecurityRole objects.
     */
    protected $collSecurityRoles;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $securityRolesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $roleUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $articleOpinionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $auditLogsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $baselineStateTableMetadatasScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $fullReviewOpinionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $fullReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $fullReviewOpinionLogsRelatedByUserIdToScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $fullReviewOpinionApprovalsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $groupOpinionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $initialReviewOpinionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $initialReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $initialReviewOpinionLogsRelatedByUserIdToScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $measurementOpinionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMQueriesRelatedByScientistIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMQueriesRelatedByDataEntry1IdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMQueriesRelatedByDataEntry2IdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $treatmentOpinionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $timepointOpinionsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->is_active = true;
    }

    /**
     * Initializes internal state of BaseUser object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {

        return $this->email;
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {

        return $this->password;
    }

    /**
     * Get the [salt] column value.
     *
     * @return string
     */
    public function getSalt()
    {

        return $this->salt;
    }

    /**
     * Get the [reset_token] column value.
     *
     * @return string
     */
    public function getResetToken()
    {

        return $this->reset_token;
    }

    /**
     * Get the [optionally formatted] temporal [reset_token_expire] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getResetTokenExpire($format = null)
    {
        if ($this->reset_token_expire === null) {
            return null;
        }

        if ($this->reset_token_expire === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->reset_token_expire);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->reset_token_expire, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [created_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedDate($format = null)
    {
        if ($this->created_date === null) {
            return null;
        }

        if ($this->created_date === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->created_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_date, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [last_login_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getLastLoginDate($format = null)
    {
        if ($this->last_login_date === null) {
            return null;
        }

        if ($this->last_login_date === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->last_login_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->last_login_date, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [is_active] column value.
     *
     * @return boolean
     */
    public function getIsActive()
    {

        return $this->is_active;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = UserPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [email] column.
     *
     * @param  string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[] = UserPeer::EMAIL;
        }


        return $this;
    } // setEmail()

    /**
     * Set the value of [password] column.
     *
     * @param  string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[] = UserPeer::PASSWORD;
        }


        return $this;
    } // setPassword()

    /**
     * Set the value of [salt] column.
     *
     * @param  string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setSalt($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->salt !== $v) {
            $this->salt = $v;
            $this->modifiedColumns[] = UserPeer::SALT;
        }


        return $this;
    } // setSalt()

    /**
     * Set the value of [reset_token] column.
     *
     * @param  string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setResetToken($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->reset_token !== $v) {
            $this->reset_token = $v;
            $this->modifiedColumns[] = UserPeer::RESET_TOKEN;
        }


        return $this;
    } // setResetToken()

    /**
     * Sets the value of [reset_token_expire] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return User The current object (for fluent API support)
     */
    public function setResetTokenExpire($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->reset_token_expire !== null || $dt !== null) {
            $currentDateAsString = ($this->reset_token_expire !== null && $tmpDt = new DateTime($this->reset_token_expire)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->reset_token_expire = $newDateAsString;
                $this->modifiedColumns[] = UserPeer::RESET_TOKEN_EXPIRE;
            }
        } // if either are not null


        return $this;
    } // setResetTokenExpire()

    /**
     * Sets the value of [created_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return User The current object (for fluent API support)
     */
    public function setCreatedDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_date !== null || $dt !== null) {
            $currentDateAsString = ($this->created_date !== null && $tmpDt = new DateTime($this->created_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_date = $newDateAsString;
                $this->modifiedColumns[] = UserPeer::CREATED_DATE;
            }
        } // if either are not null


        return $this;
    } // setCreatedDate()

    /**
     * Sets the value of [last_login_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return User The current object (for fluent API support)
     */
    public function setLastLoginDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->last_login_date !== null || $dt !== null) {
            $currentDateAsString = ($this->last_login_date !== null && $tmpDt = new DateTime($this->last_login_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->last_login_date = $newDateAsString;
                $this->modifiedColumns[] = UserPeer::LAST_LOGIN_DATE;
            }
        } // if either are not null


        return $this;
    } // setLastLoginDate()

    /**
     * Sets the value of the [is_active] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return User The current object (for fluent API support)
     */
    public function setIsActive($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_active !== $v) {
            $this->is_active = $v;
            $this->modifiedColumns[] = UserPeer::IS_ACTIVE;
        }


        return $this;
    } // setIsActive()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->is_active !== true) {
                return false;
            }

        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->email = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->password = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->salt = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->reset_token = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->reset_token_expire = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->created_date = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->last_login_date = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->is_active = ($row[$startcol + 8] !== null) ? (boolean) $row[$startcol + 8] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 9; // 9 = UserPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating User object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = UserPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collRoleUsers = null;

            $this->collArticleOpinions = null;

            $this->collAuditLogs = null;

            $this->collBaselineStateTableMetadatas = null;

            $this->collFullReviewOpinions = null;

            $this->collFullReviewOpinionLogsRelatedByUserIdFrom = null;

            $this->collFullReviewOpinionLogsRelatedByUserIdTo = null;

            $this->collFullReviewOpinionApprovals = null;

            $this->collGroupOpinions = null;

            $this->collInitialReviewOpinions = null;

            $this->collInitialReviewOpinionLogsRelatedByUserIdFrom = null;

            $this->collInitialReviewOpinionLogsRelatedByUserIdTo = null;

            $this->collMeasurementOpinions = null;

            $this->collPMQueriesRelatedByScientistId = null;

            $this->collPMQueriesRelatedByDataEntry1Id = null;

            $this->collPMQueriesRelatedByDataEntry2Id = null;

            $this->collTreatmentOpinions = null;

            $this->collTimepointOpinions = null;

            $this->singleUserDetails = null;

            $this->collSecurityRoles = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = UserQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                UserPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->securityRolesScheduledForDeletion !== null) {
                if (!$this->securityRolesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->securityRolesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    RoleUserQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->securityRolesScheduledForDeletion = null;
                }

                foreach ($this->getSecurityRoles() as $securityRole) {
                    if ($securityRole->isModified()) {
                        $securityRole->save($con);
                    }
                }
            } elseif ($this->collSecurityRoles) {
                foreach ($this->collSecurityRoles as $securityRole) {
                    if ($securityRole->isModified()) {
                        $securityRole->save($con);
                    }
                }
            }

            if ($this->roleUsersScheduledForDeletion !== null) {
                if (!$this->roleUsersScheduledForDeletion->isEmpty()) {
                    RoleUserQuery::create()
                        ->filterByPrimaryKeys($this->roleUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->roleUsersScheduledForDeletion = null;
                }
            }

            if ($this->collRoleUsers !== null) {
                foreach ($this->collRoleUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->articleOpinionsScheduledForDeletion !== null) {
                if (!$this->articleOpinionsScheduledForDeletion->isEmpty()) {
                    ArticleOpinionQuery::create()
                        ->filterByPrimaryKeys($this->articleOpinionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->articleOpinionsScheduledForDeletion = null;
                }
            }

            if ($this->collArticleOpinions !== null) {
                foreach ($this->collArticleOpinions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->auditLogsScheduledForDeletion !== null) {
                if (!$this->auditLogsScheduledForDeletion->isEmpty()) {
                    AuditLogQuery::create()
                        ->filterByPrimaryKeys($this->auditLogsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->auditLogsScheduledForDeletion = null;
                }
            }

            if ($this->collAuditLogs !== null) {
                foreach ($this->collAuditLogs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->baselineStateTableMetadatasScheduledForDeletion !== null) {
                if (!$this->baselineStateTableMetadatasScheduledForDeletion->isEmpty()) {
                    BaselineStateTableMetadataQuery::create()
                        ->filterByPrimaryKeys($this->baselineStateTableMetadatasScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->baselineStateTableMetadatasScheduledForDeletion = null;
                }
            }

            if ($this->collBaselineStateTableMetadatas !== null) {
                foreach ($this->collBaselineStateTableMetadatas as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->fullReviewOpinionsScheduledForDeletion !== null) {
                if (!$this->fullReviewOpinionsScheduledForDeletion->isEmpty()) {
                    FullReviewOpinionQuery::create()
                        ->filterByPrimaryKeys($this->fullReviewOpinionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->fullReviewOpinionsScheduledForDeletion = null;
                }
            }

            if ($this->collFullReviewOpinions !== null) {
                foreach ($this->collFullReviewOpinions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->fullReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion !== null) {
                if (!$this->fullReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion->isEmpty()) {
                    FullReviewOpinionLogQuery::create()
                        ->filterByPrimaryKeys($this->fullReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->fullReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion = null;
                }
            }

            if ($this->collFullReviewOpinionLogsRelatedByUserIdFrom !== null) {
                foreach ($this->collFullReviewOpinionLogsRelatedByUserIdFrom as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->fullReviewOpinionLogsRelatedByUserIdToScheduledForDeletion !== null) {
                if (!$this->fullReviewOpinionLogsRelatedByUserIdToScheduledForDeletion->isEmpty()) {
                    FullReviewOpinionLogQuery::create()
                        ->filterByPrimaryKeys($this->fullReviewOpinionLogsRelatedByUserIdToScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->fullReviewOpinionLogsRelatedByUserIdToScheduledForDeletion = null;
                }
            }

            if ($this->collFullReviewOpinionLogsRelatedByUserIdTo !== null) {
                foreach ($this->collFullReviewOpinionLogsRelatedByUserIdTo as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->fullReviewOpinionApprovalsScheduledForDeletion !== null) {
                if (!$this->fullReviewOpinionApprovalsScheduledForDeletion->isEmpty()) {
                    FullReviewOpinionApprovalQuery::create()
                        ->filterByPrimaryKeys($this->fullReviewOpinionApprovalsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->fullReviewOpinionApprovalsScheduledForDeletion = null;
                }
            }

            if ($this->collFullReviewOpinionApprovals !== null) {
                foreach ($this->collFullReviewOpinionApprovals as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->groupOpinionsScheduledForDeletion !== null) {
                if (!$this->groupOpinionsScheduledForDeletion->isEmpty()) {
                    foreach ($this->groupOpinionsScheduledForDeletion as $groupOpinion) {
                        // need to save related object because we set the relation to null
                        $groupOpinion->save($con);
                    }
                    $this->groupOpinionsScheduledForDeletion = null;
                }
            }

            if ($this->collGroupOpinions !== null) {
                foreach ($this->collGroupOpinions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->initialReviewOpinionsScheduledForDeletion !== null) {
                if (!$this->initialReviewOpinionsScheduledForDeletion->isEmpty()) {
                    InitialReviewOpinionQuery::create()
                        ->filterByPrimaryKeys($this->initialReviewOpinionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->initialReviewOpinionsScheduledForDeletion = null;
                }
            }

            if ($this->collInitialReviewOpinions !== null) {
                foreach ($this->collInitialReviewOpinions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->initialReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion !== null) {
                if (!$this->initialReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion->isEmpty()) {
                    InitialReviewOpinionLogQuery::create()
                        ->filterByPrimaryKeys($this->initialReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->initialReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion = null;
                }
            }

            if ($this->collInitialReviewOpinionLogsRelatedByUserIdFrom !== null) {
                foreach ($this->collInitialReviewOpinionLogsRelatedByUserIdFrom as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->initialReviewOpinionLogsRelatedByUserIdToScheduledForDeletion !== null) {
                if (!$this->initialReviewOpinionLogsRelatedByUserIdToScheduledForDeletion->isEmpty()) {
                    InitialReviewOpinionLogQuery::create()
                        ->filterByPrimaryKeys($this->initialReviewOpinionLogsRelatedByUserIdToScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->initialReviewOpinionLogsRelatedByUserIdToScheduledForDeletion = null;
                }
            }

            if ($this->collInitialReviewOpinionLogsRelatedByUserIdTo !== null) {
                foreach ($this->collInitialReviewOpinionLogsRelatedByUserIdTo as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->measurementOpinionsScheduledForDeletion !== null) {
                if (!$this->measurementOpinionsScheduledForDeletion->isEmpty()) {
                    MeasurementOpinionQuery::create()
                        ->filterByPrimaryKeys($this->measurementOpinionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->measurementOpinionsScheduledForDeletion = null;
                }
            }

            if ($this->collMeasurementOpinions !== null) {
                foreach ($this->collMeasurementOpinions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pMQueriesRelatedByScientistIdScheduledForDeletion !== null) {
                if (!$this->pMQueriesRelatedByScientistIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->pMQueriesRelatedByScientistIdScheduledForDeletion as $pMQueryRelatedByScientistId) {
                        // need to save related object because we set the relation to null
                        $pMQueryRelatedByScientistId->save($con);
                    }
                    $this->pMQueriesRelatedByScientistIdScheduledForDeletion = null;
                }
            }

            if ($this->collPMQueriesRelatedByScientistId !== null) {
                foreach ($this->collPMQueriesRelatedByScientistId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pMQueriesRelatedByDataEntry1IdScheduledForDeletion !== null) {
                if (!$this->pMQueriesRelatedByDataEntry1IdScheduledForDeletion->isEmpty()) {
                    foreach ($this->pMQueriesRelatedByDataEntry1IdScheduledForDeletion as $pMQueryRelatedByDataEntry1Id) {
                        // need to save related object because we set the relation to null
                        $pMQueryRelatedByDataEntry1Id->save($con);
                    }
                    $this->pMQueriesRelatedByDataEntry1IdScheduledForDeletion = null;
                }
            }

            if ($this->collPMQueriesRelatedByDataEntry1Id !== null) {
                foreach ($this->collPMQueriesRelatedByDataEntry1Id as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pMQueriesRelatedByDataEntry2IdScheduledForDeletion !== null) {
                if (!$this->pMQueriesRelatedByDataEntry2IdScheduledForDeletion->isEmpty()) {
                    foreach ($this->pMQueriesRelatedByDataEntry2IdScheduledForDeletion as $pMQueryRelatedByDataEntry2Id) {
                        // need to save related object because we set the relation to null
                        $pMQueryRelatedByDataEntry2Id->save($con);
                    }
                    $this->pMQueriesRelatedByDataEntry2IdScheduledForDeletion = null;
                }
            }

            if ($this->collPMQueriesRelatedByDataEntry2Id !== null) {
                foreach ($this->collPMQueriesRelatedByDataEntry2Id as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->treatmentOpinionsScheduledForDeletion !== null) {
                if (!$this->treatmentOpinionsScheduledForDeletion->isEmpty()) {
                    TreatmentOpinionQuery::create()
                        ->filterByPrimaryKeys($this->treatmentOpinionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->treatmentOpinionsScheduledForDeletion = null;
                }
            }

            if ($this->collTreatmentOpinions !== null) {
                foreach ($this->collTreatmentOpinions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->timepointOpinionsScheduledForDeletion !== null) {
                if (!$this->timepointOpinionsScheduledForDeletion->isEmpty()) {
                    foreach ($this->timepointOpinionsScheduledForDeletion as $timepointOpinion) {
                        // need to save related object because we set the relation to null
                        $timepointOpinion->save($con);
                    }
                    $this->timepointOpinionsScheduledForDeletion = null;
                }
            }

            if ($this->collTimepointOpinions !== null) {
                foreach ($this->collTimepointOpinions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->singleUserDetails !== null) {
                if (!$this->singleUserDetails->isDeleted() && ($this->singleUserDetails->isNew() || $this->singleUserDetails->isModified())) {
                        $affectedRows += $this->singleUserDetails->save($con);
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = UserPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(UserPeer::EMAIL)) {
            $modifiedColumns[':p' . $index++]  = '`email`';
        }
        if ($this->isColumnModified(UserPeer::PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = '`password`';
        }
        if ($this->isColumnModified(UserPeer::SALT)) {
            $modifiedColumns[':p' . $index++]  = '`salt`';
        }
        if ($this->isColumnModified(UserPeer::RESET_TOKEN)) {
            $modifiedColumns[':p' . $index++]  = '`reset_token`';
        }
        if ($this->isColumnModified(UserPeer::RESET_TOKEN_EXPIRE)) {
            $modifiedColumns[':p' . $index++]  = '`reset_token_expire`';
        }
        if ($this->isColumnModified(UserPeer::CREATED_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`created_date`';
        }
        if ($this->isColumnModified(UserPeer::LAST_LOGIN_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`last_login_date`';
        }
        if ($this->isColumnModified(UserPeer::IS_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = '`is_active`';
        }

        $sql = sprintf(
            'INSERT INTO `users` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`email`':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case '`password`':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case '`salt`':
                        $stmt->bindValue($identifier, $this->salt, PDO::PARAM_STR);
                        break;
                    case '`reset_token`':
                        $stmt->bindValue($identifier, $this->reset_token, PDO::PARAM_STR);
                        break;
                    case '`reset_token_expire`':
                        $stmt->bindValue($identifier, $this->reset_token_expire, PDO::PARAM_STR);
                        break;
                    case '`created_date`':
                        $stmt->bindValue($identifier, $this->created_date, PDO::PARAM_STR);
                        break;
                    case '`last_login_date`':
                        $stmt->bindValue($identifier, $this->last_login_date, PDO::PARAM_STR);
                        break;
                    case '`is_active`':
                        $stmt->bindValue($identifier, (int) $this->is_active, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            if (($retval = UserPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collRoleUsers !== null) {
                    foreach ($this->collRoleUsers as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collArticleOpinions !== null) {
                    foreach ($this->collArticleOpinions as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collAuditLogs !== null) {
                    foreach ($this->collAuditLogs as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBaselineStateTableMetadatas !== null) {
                    foreach ($this->collBaselineStateTableMetadatas as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collFullReviewOpinions !== null) {
                    foreach ($this->collFullReviewOpinions as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collFullReviewOpinionLogsRelatedByUserIdFrom !== null) {
                    foreach ($this->collFullReviewOpinionLogsRelatedByUserIdFrom as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collFullReviewOpinionLogsRelatedByUserIdTo !== null) {
                    foreach ($this->collFullReviewOpinionLogsRelatedByUserIdTo as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collFullReviewOpinionApprovals !== null) {
                    foreach ($this->collFullReviewOpinionApprovals as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collGroupOpinions !== null) {
                    foreach ($this->collGroupOpinions as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collInitialReviewOpinions !== null) {
                    foreach ($this->collInitialReviewOpinions as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collInitialReviewOpinionLogsRelatedByUserIdFrom !== null) {
                    foreach ($this->collInitialReviewOpinionLogsRelatedByUserIdFrom as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collInitialReviewOpinionLogsRelatedByUserIdTo !== null) {
                    foreach ($this->collInitialReviewOpinionLogsRelatedByUserIdTo as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collMeasurementOpinions !== null) {
                    foreach ($this->collMeasurementOpinions as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPMQueriesRelatedByScientistId !== null) {
                    foreach ($this->collPMQueriesRelatedByScientistId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPMQueriesRelatedByDataEntry1Id !== null) {
                    foreach ($this->collPMQueriesRelatedByDataEntry1Id as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPMQueriesRelatedByDataEntry2Id !== null) {
                    foreach ($this->collPMQueriesRelatedByDataEntry2Id as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collTreatmentOpinions !== null) {
                    foreach ($this->collTreatmentOpinions as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collTimepointOpinions !== null) {
                    foreach ($this->collTimepointOpinions as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->singleUserDetails !== null) {
                    if (!$this->singleUserDetails->validate($columns)) {
                        $failureMap = array_merge($failureMap, $this->singleUserDetails->getValidationFailures());
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = UserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getEmail();
                break;
            case 2:
                return $this->getPassword();
                break;
            case 3:
                return $this->getSalt();
                break;
            case 4:
                return $this->getResetToken();
                break;
            case 5:
                return $this->getResetTokenExpire();
                break;
            case 6:
                return $this->getCreatedDate();
                break;
            case 7:
                return $this->getLastLoginDate();
                break;
            case 8:
                return $this->getIsActive();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['User'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->getPrimaryKey()] = true;
        $keys = UserPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getEmail(),
            $keys[2] => $this->getPassword(),
            $keys[3] => $this->getSalt(),
            $keys[4] => $this->getResetToken(),
            $keys[5] => $this->getResetTokenExpire(),
            $keys[6] => $this->getCreatedDate(),
            $keys[7] => $this->getLastLoginDate(),
            $keys[8] => $this->getIsActive(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collRoleUsers) {
                $result['RoleUsers'] = $this->collRoleUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collArticleOpinions) {
                $result['ArticleOpinions'] = $this->collArticleOpinions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAuditLogs) {
                $result['AuditLogs'] = $this->collAuditLogs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collBaselineStateTableMetadatas) {
                $result['BaselineStateTableMetadatas'] = $this->collBaselineStateTableMetadatas->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFullReviewOpinions) {
                $result['FullReviewOpinions'] = $this->collFullReviewOpinions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFullReviewOpinionLogsRelatedByUserIdFrom) {
                $result['FullReviewOpinionLogsRelatedByUserIdFrom'] = $this->collFullReviewOpinionLogsRelatedByUserIdFrom->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFullReviewOpinionLogsRelatedByUserIdTo) {
                $result['FullReviewOpinionLogsRelatedByUserIdTo'] = $this->collFullReviewOpinionLogsRelatedByUserIdTo->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFullReviewOpinionApprovals) {
                $result['FullReviewOpinionApprovals'] = $this->collFullReviewOpinionApprovals->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collGroupOpinions) {
                $result['GroupOpinions'] = $this->collGroupOpinions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collInitialReviewOpinions) {
                $result['InitialReviewOpinions'] = $this->collInitialReviewOpinions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collInitialReviewOpinionLogsRelatedByUserIdFrom) {
                $result['InitialReviewOpinionLogsRelatedByUserIdFrom'] = $this->collInitialReviewOpinionLogsRelatedByUserIdFrom->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collInitialReviewOpinionLogsRelatedByUserIdTo) {
                $result['InitialReviewOpinionLogsRelatedByUserIdTo'] = $this->collInitialReviewOpinionLogsRelatedByUserIdTo->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMeasurementOpinions) {
                $result['MeasurementOpinions'] = $this->collMeasurementOpinions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPMQueriesRelatedByScientistId) {
                $result['PMQueriesRelatedByScientistId'] = $this->collPMQueriesRelatedByScientistId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPMQueriesRelatedByDataEntry1Id) {
                $result['PMQueriesRelatedByDataEntry1Id'] = $this->collPMQueriesRelatedByDataEntry1Id->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPMQueriesRelatedByDataEntry2Id) {
                $result['PMQueriesRelatedByDataEntry2Id'] = $this->collPMQueriesRelatedByDataEntry2Id->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTreatmentOpinions) {
                $result['TreatmentOpinions'] = $this->collTreatmentOpinions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTimepointOpinions) {
                $result['TimepointOpinions'] = $this->collTimepointOpinions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->singleUserDetails) {
                $result['UserDetails'] = $this->singleUserDetails->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = UserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setEmail($value);
                break;
            case 2:
                $this->setPassword($value);
                break;
            case 3:
                $this->setSalt($value);
                break;
            case 4:
                $this->setResetToken($value);
                break;
            case 5:
                $this->setResetTokenExpire($value);
                break;
            case 6:
                $this->setCreatedDate($value);
                break;
            case 7:
                $this->setLastLoginDate($value);
                break;
            case 8:
                $this->setIsActive($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = UserPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setEmail($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPassword($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setSalt($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setResetToken($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setResetTokenExpire($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setCreatedDate($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setLastLoginDate($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setIsActive($arr[$keys[8]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(UserPeer::DATABASE_NAME);

        if ($this->isColumnModified(UserPeer::ID)) $criteria->add(UserPeer::ID, $this->id);
        if ($this->isColumnModified(UserPeer::EMAIL)) $criteria->add(UserPeer::EMAIL, $this->email);
        if ($this->isColumnModified(UserPeer::PASSWORD)) $criteria->add(UserPeer::PASSWORD, $this->password);
        if ($this->isColumnModified(UserPeer::SALT)) $criteria->add(UserPeer::SALT, $this->salt);
        if ($this->isColumnModified(UserPeer::RESET_TOKEN)) $criteria->add(UserPeer::RESET_TOKEN, $this->reset_token);
        if ($this->isColumnModified(UserPeer::RESET_TOKEN_EXPIRE)) $criteria->add(UserPeer::RESET_TOKEN_EXPIRE, $this->reset_token_expire);
        if ($this->isColumnModified(UserPeer::CREATED_DATE)) $criteria->add(UserPeer::CREATED_DATE, $this->created_date);
        if ($this->isColumnModified(UserPeer::LAST_LOGIN_DATE)) $criteria->add(UserPeer::LAST_LOGIN_DATE, $this->last_login_date);
        if ($this->isColumnModified(UserPeer::IS_ACTIVE)) $criteria->add(UserPeer::IS_ACTIVE, $this->is_active);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(UserPeer::DATABASE_NAME);
        $criteria->add(UserPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of User (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEmail($this->getEmail());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setSalt($this->getSalt());
        $copyObj->setResetToken($this->getResetToken());
        $copyObj->setResetTokenExpire($this->getResetTokenExpire());
        $copyObj->setCreatedDate($this->getCreatedDate());
        $copyObj->setLastLoginDate($this->getLastLoginDate());
        $copyObj->setIsActive($this->getIsActive());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getRoleUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRoleUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getArticleOpinions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addArticleOpinion($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAuditLogs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAuditLog($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getBaselineStateTableMetadatas() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addBaselineStateTableMetadata($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFullReviewOpinions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFullReviewOpinion($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFullReviewOpinionLogsRelatedByUserIdFrom() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFullReviewOpinionLogRelatedByUserIdFrom($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFullReviewOpinionLogsRelatedByUserIdTo() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFullReviewOpinionLogRelatedByUserIdTo($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFullReviewOpinionApprovals() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFullReviewOpinionApproval($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getGroupOpinions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGroupOpinion($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getInitialReviewOpinions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInitialReviewOpinion($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getInitialReviewOpinionLogsRelatedByUserIdFrom() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInitialReviewOpinionLogRelatedByUserIdFrom($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getInitialReviewOpinionLogsRelatedByUserIdTo() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInitialReviewOpinionLogRelatedByUserIdTo($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMeasurementOpinions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMeasurementOpinion($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPMQueriesRelatedByScientistId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMQueryRelatedByScientistId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPMQueriesRelatedByDataEntry1Id() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMQueryRelatedByDataEntry1Id($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPMQueriesRelatedByDataEntry2Id() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMQueryRelatedByDataEntry2Id($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTreatmentOpinions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTreatmentOpinion($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTimepointOpinions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTimepointOpinion($relObj->copy($deepCopy));
                }
            }

            $relObj = $this->getUserDetails();
            if ($relObj) {
                $copyObj->setUserDetails($relObj->copy($deepCopy));
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return User Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return UserPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new UserPeer();
        }

        return self::$peer;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('RoleUser' == $relationName) {
            $this->initRoleUsers();
        }
        if ('ArticleOpinion' == $relationName) {
            $this->initArticleOpinions();
        }
        if ('AuditLog' == $relationName) {
            $this->initAuditLogs();
        }
        if ('BaselineStateTableMetadata' == $relationName) {
            $this->initBaselineStateTableMetadatas();
        }
        if ('FullReviewOpinion' == $relationName) {
            $this->initFullReviewOpinions();
        }
        if ('FullReviewOpinionLogRelatedByUserIdFrom' == $relationName) {
            $this->initFullReviewOpinionLogsRelatedByUserIdFrom();
        }
        if ('FullReviewOpinionLogRelatedByUserIdTo' == $relationName) {
            $this->initFullReviewOpinionLogsRelatedByUserIdTo();
        }
        if ('FullReviewOpinionApproval' == $relationName) {
            $this->initFullReviewOpinionApprovals();
        }
        if ('GroupOpinion' == $relationName) {
            $this->initGroupOpinions();
        }
        if ('InitialReviewOpinion' == $relationName) {
            $this->initInitialReviewOpinions();
        }
        if ('InitialReviewOpinionLogRelatedByUserIdFrom' == $relationName) {
            $this->initInitialReviewOpinionLogsRelatedByUserIdFrom();
        }
        if ('InitialReviewOpinionLogRelatedByUserIdTo' == $relationName) {
            $this->initInitialReviewOpinionLogsRelatedByUserIdTo();
        }
        if ('MeasurementOpinion' == $relationName) {
            $this->initMeasurementOpinions();
        }
        if ('PMQueryRelatedByScientistId' == $relationName) {
            $this->initPMQueriesRelatedByScientistId();
        }
        if ('PMQueryRelatedByDataEntry1Id' == $relationName) {
            $this->initPMQueriesRelatedByDataEntry1Id();
        }
        if ('PMQueryRelatedByDataEntry2Id' == $relationName) {
            $this->initPMQueriesRelatedByDataEntry2Id();
        }
        if ('TreatmentOpinion' == $relationName) {
            $this->initTreatmentOpinions();
        }
        if ('TimepointOpinion' == $relationName) {
            $this->initTimepointOpinions();
        }
    }

    /**
     * Clears out the collRoleUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addRoleUsers()
     */
    public function clearRoleUsers()
    {
        $this->collRoleUsers = null; // important to set this to null since that means it is uninitialized
        $this->collRoleUsersPartial = null;

        return $this;
    }

    /**
     * reset is the collRoleUsers collection loaded partially
     *
     * @return void
     */
    public function resetPartialRoleUsers($v = true)
    {
        $this->collRoleUsersPartial = $v;
    }

    /**
     * Initializes the collRoleUsers collection.
     *
     * By default this just sets the collRoleUsers collection to an empty array (like clearcollRoleUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRoleUsers($overrideExisting = true)
    {
        if (null !== $this->collRoleUsers && !$overrideExisting) {
            return;
        }
        $this->collRoleUsers = new PropelObjectCollection();
        $this->collRoleUsers->setModel('RoleUser');
    }

    /**
     * Gets an array of RoleUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|RoleUser[] List of RoleUser objects
     * @throws PropelException
     */
    public function getRoleUsers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collRoleUsersPartial && !$this->isNew();
        if (null === $this->collRoleUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collRoleUsers) {
                // return empty collection
                $this->initRoleUsers();
            } else {
                $collRoleUsers = RoleUserQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collRoleUsersPartial && count($collRoleUsers)) {
                      $this->initRoleUsers(false);

                      foreach ($collRoleUsers as $obj) {
                        if (false == $this->collRoleUsers->contains($obj)) {
                          $this->collRoleUsers->append($obj);
                        }
                      }

                      $this->collRoleUsersPartial = true;
                    }

                    $collRoleUsers->getInternalIterator()->rewind();

                    return $collRoleUsers;
                }

                if ($partial && $this->collRoleUsers) {
                    foreach ($this->collRoleUsers as $obj) {
                        if ($obj->isNew()) {
                            $collRoleUsers[] = $obj;
                        }
                    }
                }

                $this->collRoleUsers = $collRoleUsers;
                $this->collRoleUsersPartial = false;
            }
        }

        return $this->collRoleUsers;
    }

    /**
     * Sets a collection of RoleUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $roleUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setRoleUsers(PropelCollection $roleUsers, PropelPDO $con = null)
    {
        $roleUsersToDelete = $this->getRoleUsers(new Criteria(), $con)->diff($roleUsers);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->roleUsersScheduledForDeletion = clone $roleUsersToDelete;

        foreach ($roleUsersToDelete as $roleUserRemoved) {
            $roleUserRemoved->setUser(null);
        }

        $this->collRoleUsers = null;
        foreach ($roleUsers as $roleUser) {
            $this->addRoleUser($roleUser);
        }

        $this->collRoleUsers = $roleUsers;
        $this->collRoleUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related RoleUser objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related RoleUser objects.
     * @throws PropelException
     */
    public function countRoleUsers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collRoleUsersPartial && !$this->isNew();
        if (null === $this->collRoleUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRoleUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getRoleUsers());
            }
            $query = RoleUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collRoleUsers);
    }

    /**
     * Method called to associate a RoleUser object to this object
     * through the RoleUser foreign key attribute.
     *
     * @param    RoleUser $l RoleUser
     * @return User The current object (for fluent API support)
     */
    public function addRoleUser(RoleUser $l)
    {
        if ($this->collRoleUsers === null) {
            $this->initRoleUsers();
            $this->collRoleUsersPartial = true;
        }

        if (!in_array($l, $this->collRoleUsers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddRoleUser($l);

            if ($this->roleUsersScheduledForDeletion and $this->roleUsersScheduledForDeletion->contains($l)) {
                $this->roleUsersScheduledForDeletion->remove($this->roleUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	RoleUser $roleUser The roleUser object to add.
     */
    protected function doAddRoleUser($roleUser)
    {
        $this->collRoleUsers[]= $roleUser;
        $roleUser->setUser($this);
    }

    /**
     * @param	RoleUser $roleUser The roleUser object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeRoleUser($roleUser)
    {
        if ($this->getRoleUsers()->contains($roleUser)) {
            $this->collRoleUsers->remove($this->collRoleUsers->search($roleUser));
            if (null === $this->roleUsersScheduledForDeletion) {
                $this->roleUsersScheduledForDeletion = clone $this->collRoleUsers;
                $this->roleUsersScheduledForDeletion->clear();
            }
            $this->roleUsersScheduledForDeletion[]= clone $roleUser;
            $roleUser->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related RoleUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|RoleUser[] List of RoleUser objects
     */
    public function getRoleUsersJoinSecurityRole($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = RoleUserQuery::create(null, $criteria);
        $query->joinWith('SecurityRole', $join_behavior);

        return $this->getRoleUsers($query, $con);
    }

    /**
     * Clears out the collArticleOpinions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addArticleOpinions()
     */
    public function clearArticleOpinions()
    {
        $this->collArticleOpinions = null; // important to set this to null since that means it is uninitialized
        $this->collArticleOpinionsPartial = null;

        return $this;
    }

    /**
     * reset is the collArticleOpinions collection loaded partially
     *
     * @return void
     */
    public function resetPartialArticleOpinions($v = true)
    {
        $this->collArticleOpinionsPartial = $v;
    }

    /**
     * Initializes the collArticleOpinions collection.
     *
     * By default this just sets the collArticleOpinions collection to an empty array (like clearcollArticleOpinions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initArticleOpinions($overrideExisting = true)
    {
        if (null !== $this->collArticleOpinions && !$overrideExisting) {
            return;
        }
        $this->collArticleOpinions = new PropelObjectCollection();
        $this->collArticleOpinions->setModel('ArticleOpinion');
    }

    /**
     * Gets an array of ArticleOpinion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|ArticleOpinion[] List of ArticleOpinion objects
     * @throws PropelException
     */
    public function getArticleOpinions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collArticleOpinionsPartial && !$this->isNew();
        if (null === $this->collArticleOpinions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collArticleOpinions) {
                // return empty collection
                $this->initArticleOpinions();
            } else {
                $collArticleOpinions = ArticleOpinionQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collArticleOpinionsPartial && count($collArticleOpinions)) {
                      $this->initArticleOpinions(false);

                      foreach ($collArticleOpinions as $obj) {
                        if (false == $this->collArticleOpinions->contains($obj)) {
                          $this->collArticleOpinions->append($obj);
                        }
                      }

                      $this->collArticleOpinionsPartial = true;
                    }

                    $collArticleOpinions->getInternalIterator()->rewind();

                    return $collArticleOpinions;
                }

                if ($partial && $this->collArticleOpinions) {
                    foreach ($this->collArticleOpinions as $obj) {
                        if ($obj->isNew()) {
                            $collArticleOpinions[] = $obj;
                        }
                    }
                }

                $this->collArticleOpinions = $collArticleOpinions;
                $this->collArticleOpinionsPartial = false;
            }
        }

        return $this->collArticleOpinions;
    }

    /**
     * Sets a collection of ArticleOpinion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $articleOpinions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setArticleOpinions(PropelCollection $articleOpinions, PropelPDO $con = null)
    {
        $articleOpinionsToDelete = $this->getArticleOpinions(new Criteria(), $con)->diff($articleOpinions);


        $this->articleOpinionsScheduledForDeletion = $articleOpinionsToDelete;

        foreach ($articleOpinionsToDelete as $articleOpinionRemoved) {
            $articleOpinionRemoved->setUser(null);
        }

        $this->collArticleOpinions = null;
        foreach ($articleOpinions as $articleOpinion) {
            $this->addArticleOpinion($articleOpinion);
        }

        $this->collArticleOpinions = $articleOpinions;
        $this->collArticleOpinionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ArticleOpinion objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related ArticleOpinion objects.
     * @throws PropelException
     */
    public function countArticleOpinions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collArticleOpinionsPartial && !$this->isNew();
        if (null === $this->collArticleOpinions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collArticleOpinions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getArticleOpinions());
            }
            $query = ArticleOpinionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collArticleOpinions);
    }

    /**
     * Method called to associate a ArticleOpinion object to this object
     * through the ArticleOpinion foreign key attribute.
     *
     * @param    ArticleOpinion $l ArticleOpinion
     * @return User The current object (for fluent API support)
     */
    public function addArticleOpinion(ArticleOpinion $l)
    {
        if ($this->collArticleOpinions === null) {
            $this->initArticleOpinions();
            $this->collArticleOpinionsPartial = true;
        }

        if (!in_array($l, $this->collArticleOpinions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddArticleOpinion($l);

            if ($this->articleOpinionsScheduledForDeletion and $this->articleOpinionsScheduledForDeletion->contains($l)) {
                $this->articleOpinionsScheduledForDeletion->remove($this->articleOpinionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	ArticleOpinion $articleOpinion The articleOpinion object to add.
     */
    protected function doAddArticleOpinion($articleOpinion)
    {
        $this->collArticleOpinions[]= $articleOpinion;
        $articleOpinion->setUser($this);
    }

    /**
     * @param	ArticleOpinion $articleOpinion The articleOpinion object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeArticleOpinion($articleOpinion)
    {
        if ($this->getArticleOpinions()->contains($articleOpinion)) {
            $this->collArticleOpinions->remove($this->collArticleOpinions->search($articleOpinion));
            if (null === $this->articleOpinionsScheduledForDeletion) {
                $this->articleOpinionsScheduledForDeletion = clone $this->collArticleOpinions;
                $this->articleOpinionsScheduledForDeletion->clear();
            }
            $this->articleOpinionsScheduledForDeletion[]= clone $articleOpinion;
            $articleOpinion->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related ArticleOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ArticleOpinion[] List of ArticleOpinion objects
     */
    public function getArticleOpinionsJoinArticle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ArticleOpinionQuery::create(null, $criteria);
        $query->joinWith('Article', $join_behavior);

        return $this->getArticleOpinions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related ArticleOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ArticleOpinion[] List of ArticleOpinion objects
     */
    public function getArticleOpinionsJoinNamedStudy($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ArticleOpinionQuery::create(null, $criteria);
        $query->joinWith('NamedStudy', $join_behavior);

        return $this->getArticleOpinions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related ArticleOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ArticleOpinion[] List of ArticleOpinion objects
     */
    public function getArticleOpinionsJoinStudyClass($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ArticleOpinionQuery::create(null, $criteria);
        $query->joinWith('StudyClass', $join_behavior);

        return $this->getArticleOpinions($query, $con);
    }

    /**
     * Clears out the collAuditLogs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addAuditLogs()
     */
    public function clearAuditLogs()
    {
        $this->collAuditLogs = null; // important to set this to null since that means it is uninitialized
        $this->collAuditLogsPartial = null;

        return $this;
    }

    /**
     * reset is the collAuditLogs collection loaded partially
     *
     * @return void
     */
    public function resetPartialAuditLogs($v = true)
    {
        $this->collAuditLogsPartial = $v;
    }

    /**
     * Initializes the collAuditLogs collection.
     *
     * By default this just sets the collAuditLogs collection to an empty array (like clearcollAuditLogs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAuditLogs($overrideExisting = true)
    {
        if (null !== $this->collAuditLogs && !$overrideExisting) {
            return;
        }
        $this->collAuditLogs = new PropelObjectCollection();
        $this->collAuditLogs->setModel('AuditLog');
    }

    /**
     * Gets an array of AuditLog objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|AuditLog[] List of AuditLog objects
     * @throws PropelException
     */
    public function getAuditLogs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collAuditLogsPartial && !$this->isNew();
        if (null === $this->collAuditLogs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAuditLogs) {
                // return empty collection
                $this->initAuditLogs();
            } else {
                $collAuditLogs = AuditLogQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collAuditLogsPartial && count($collAuditLogs)) {
                      $this->initAuditLogs(false);

                      foreach ($collAuditLogs as $obj) {
                        if (false == $this->collAuditLogs->contains($obj)) {
                          $this->collAuditLogs->append($obj);
                        }
                      }

                      $this->collAuditLogsPartial = true;
                    }

                    $collAuditLogs->getInternalIterator()->rewind();

                    return $collAuditLogs;
                }

                if ($partial && $this->collAuditLogs) {
                    foreach ($this->collAuditLogs as $obj) {
                        if ($obj->isNew()) {
                            $collAuditLogs[] = $obj;
                        }
                    }
                }

                $this->collAuditLogs = $collAuditLogs;
                $this->collAuditLogsPartial = false;
            }
        }

        return $this->collAuditLogs;
    }

    /**
     * Sets a collection of AuditLog objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $auditLogs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setAuditLogs(PropelCollection $auditLogs, PropelPDO $con = null)
    {
        $auditLogsToDelete = $this->getAuditLogs(new Criteria(), $con)->diff($auditLogs);


        $this->auditLogsScheduledForDeletion = $auditLogsToDelete;

        foreach ($auditLogsToDelete as $auditLogRemoved) {
            $auditLogRemoved->setUser(null);
        }

        $this->collAuditLogs = null;
        foreach ($auditLogs as $auditLog) {
            $this->addAuditLog($auditLog);
        }

        $this->collAuditLogs = $auditLogs;
        $this->collAuditLogsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related AuditLog objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related AuditLog objects.
     * @throws PropelException
     */
    public function countAuditLogs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collAuditLogsPartial && !$this->isNew();
        if (null === $this->collAuditLogs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAuditLogs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAuditLogs());
            }
            $query = AuditLogQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collAuditLogs);
    }

    /**
     * Method called to associate a AuditLog object to this object
     * through the AuditLog foreign key attribute.
     *
     * @param    AuditLog $l AuditLog
     * @return User The current object (for fluent API support)
     */
    public function addAuditLog(AuditLog $l)
    {
        if ($this->collAuditLogs === null) {
            $this->initAuditLogs();
            $this->collAuditLogsPartial = true;
        }

        if (!in_array($l, $this->collAuditLogs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddAuditLog($l);

            if ($this->auditLogsScheduledForDeletion and $this->auditLogsScheduledForDeletion->contains($l)) {
                $this->auditLogsScheduledForDeletion->remove($this->auditLogsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	AuditLog $auditLog The auditLog object to add.
     */
    protected function doAddAuditLog($auditLog)
    {
        $this->collAuditLogs[]= $auditLog;
        $auditLog->setUser($this);
    }

    /**
     * @param	AuditLog $auditLog The auditLog object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeAuditLog($auditLog)
    {
        if ($this->getAuditLogs()->contains($auditLog)) {
            $this->collAuditLogs->remove($this->collAuditLogs->search($auditLog));
            if (null === $this->auditLogsScheduledForDeletion) {
                $this->auditLogsScheduledForDeletion = clone $this->collAuditLogs;
                $this->auditLogsScheduledForDeletion->clear();
            }
            $this->auditLogsScheduledForDeletion[]= clone $auditLog;
            $auditLog->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related AuditLogs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|AuditLog[] List of AuditLog objects
     */
    public function getAuditLogsJoinArticle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = AuditLogQuery::create(null, $criteria);
        $query->joinWith('Article', $join_behavior);

        return $this->getAuditLogs($query, $con);
    }

    /**
     * Clears out the collBaselineStateTableMetadatas collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addBaselineStateTableMetadatas()
     */
    public function clearBaselineStateTableMetadatas()
    {
        $this->collBaselineStateTableMetadatas = null; // important to set this to null since that means it is uninitialized
        $this->collBaselineStateTableMetadatasPartial = null;

        return $this;
    }

    /**
     * reset is the collBaselineStateTableMetadatas collection loaded partially
     *
     * @return void
     */
    public function resetPartialBaselineStateTableMetadatas($v = true)
    {
        $this->collBaselineStateTableMetadatasPartial = $v;
    }

    /**
     * Initializes the collBaselineStateTableMetadatas collection.
     *
     * By default this just sets the collBaselineStateTableMetadatas collection to an empty array (like clearcollBaselineStateTableMetadatas());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initBaselineStateTableMetadatas($overrideExisting = true)
    {
        if (null !== $this->collBaselineStateTableMetadatas && !$overrideExisting) {
            return;
        }
        $this->collBaselineStateTableMetadatas = new PropelObjectCollection();
        $this->collBaselineStateTableMetadatas->setModel('BaselineStateTableMetadata');
    }

    /**
     * Gets an array of BaselineStateTableMetadata objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|BaselineStateTableMetadata[] List of BaselineStateTableMetadata objects
     * @throws PropelException
     */
    public function getBaselineStateTableMetadatas($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collBaselineStateTableMetadatasPartial && !$this->isNew();
        if (null === $this->collBaselineStateTableMetadatas || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collBaselineStateTableMetadatas) {
                // return empty collection
                $this->initBaselineStateTableMetadatas();
            } else {
                $collBaselineStateTableMetadatas = BaselineStateTableMetadataQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collBaselineStateTableMetadatasPartial && count($collBaselineStateTableMetadatas)) {
                      $this->initBaselineStateTableMetadatas(false);

                      foreach ($collBaselineStateTableMetadatas as $obj) {
                        if (false == $this->collBaselineStateTableMetadatas->contains($obj)) {
                          $this->collBaselineStateTableMetadatas->append($obj);
                        }
                      }

                      $this->collBaselineStateTableMetadatasPartial = true;
                    }

                    $collBaselineStateTableMetadatas->getInternalIterator()->rewind();

                    return $collBaselineStateTableMetadatas;
                }

                if ($partial && $this->collBaselineStateTableMetadatas) {
                    foreach ($this->collBaselineStateTableMetadatas as $obj) {
                        if ($obj->isNew()) {
                            $collBaselineStateTableMetadatas[] = $obj;
                        }
                    }
                }

                $this->collBaselineStateTableMetadatas = $collBaselineStateTableMetadatas;
                $this->collBaselineStateTableMetadatasPartial = false;
            }
        }

        return $this->collBaselineStateTableMetadatas;
    }

    /**
     * Sets a collection of BaselineStateTableMetadata objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $baselineStateTableMetadatas A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setBaselineStateTableMetadatas(PropelCollection $baselineStateTableMetadatas, PropelPDO $con = null)
    {
        $baselineStateTableMetadatasToDelete = $this->getBaselineStateTableMetadatas(new Criteria(), $con)->diff($baselineStateTableMetadatas);


        $this->baselineStateTableMetadatasScheduledForDeletion = $baselineStateTableMetadatasToDelete;

        foreach ($baselineStateTableMetadatasToDelete as $baselineStateTableMetadataRemoved) {
            $baselineStateTableMetadataRemoved->setUser(null);
        }

        $this->collBaselineStateTableMetadatas = null;
        foreach ($baselineStateTableMetadatas as $baselineStateTableMetadata) {
            $this->addBaselineStateTableMetadata($baselineStateTableMetadata);
        }

        $this->collBaselineStateTableMetadatas = $baselineStateTableMetadatas;
        $this->collBaselineStateTableMetadatasPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaselineStateTableMetadata objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related BaselineStateTableMetadata objects.
     * @throws PropelException
     */
    public function countBaselineStateTableMetadatas(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collBaselineStateTableMetadatasPartial && !$this->isNew();
        if (null === $this->collBaselineStateTableMetadatas || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collBaselineStateTableMetadatas) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getBaselineStateTableMetadatas());
            }
            $query = BaselineStateTableMetadataQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collBaselineStateTableMetadatas);
    }

    /**
     * Method called to associate a BaselineStateTableMetadata object to this object
     * through the BaselineStateTableMetadata foreign key attribute.
     *
     * @param    BaselineStateTableMetadata $l BaselineStateTableMetadata
     * @return User The current object (for fluent API support)
     */
    public function addBaselineStateTableMetadata(BaselineStateTableMetadata $l)
    {
        if ($this->collBaselineStateTableMetadatas === null) {
            $this->initBaselineStateTableMetadatas();
            $this->collBaselineStateTableMetadatasPartial = true;
        }

        if (!in_array($l, $this->collBaselineStateTableMetadatas->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddBaselineStateTableMetadata($l);

            if ($this->baselineStateTableMetadatasScheduledForDeletion and $this->baselineStateTableMetadatasScheduledForDeletion->contains($l)) {
                $this->baselineStateTableMetadatasScheduledForDeletion->remove($this->baselineStateTableMetadatasScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	BaselineStateTableMetadata $baselineStateTableMetadata The baselineStateTableMetadata object to add.
     */
    protected function doAddBaselineStateTableMetadata($baselineStateTableMetadata)
    {
        $this->collBaselineStateTableMetadatas[]= $baselineStateTableMetadata;
        $baselineStateTableMetadata->setUser($this);
    }

    /**
     * @param	BaselineStateTableMetadata $baselineStateTableMetadata The baselineStateTableMetadata object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeBaselineStateTableMetadata($baselineStateTableMetadata)
    {
        if ($this->getBaselineStateTableMetadatas()->contains($baselineStateTableMetadata)) {
            $this->collBaselineStateTableMetadatas->remove($this->collBaselineStateTableMetadatas->search($baselineStateTableMetadata));
            if (null === $this->baselineStateTableMetadatasScheduledForDeletion) {
                $this->baselineStateTableMetadatasScheduledForDeletion = clone $this->collBaselineStateTableMetadatas;
                $this->baselineStateTableMetadatasScheduledForDeletion->clear();
            }
            $this->baselineStateTableMetadatasScheduledForDeletion[]= clone $baselineStateTableMetadata;
            $baselineStateTableMetadata->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related BaselineStateTableMetadatas from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|BaselineStateTableMetadata[] List of BaselineStateTableMetadata objects
     */
    public function getBaselineStateTableMetadatasJoinArticle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BaselineStateTableMetadataQuery::create(null, $criteria);
        $query->joinWith('Article', $join_behavior);

        return $this->getBaselineStateTableMetadatas($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related BaselineStateTableMetadatas from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|BaselineStateTableMetadata[] List of BaselineStateTableMetadata objects
     */
    public function getBaselineStateTableMetadatasJoinTimepointOpinion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BaselineStateTableMetadataQuery::create(null, $criteria);
        $query->joinWith('TimepointOpinion', $join_behavior);

        return $this->getBaselineStateTableMetadatas($query, $con);
    }

    /**
     * Clears out the collFullReviewOpinions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addFullReviewOpinions()
     */
    public function clearFullReviewOpinions()
    {
        $this->collFullReviewOpinions = null; // important to set this to null since that means it is uninitialized
        $this->collFullReviewOpinionsPartial = null;

        return $this;
    }

    /**
     * reset is the collFullReviewOpinions collection loaded partially
     *
     * @return void
     */
    public function resetPartialFullReviewOpinions($v = true)
    {
        $this->collFullReviewOpinionsPartial = $v;
    }

    /**
     * Initializes the collFullReviewOpinions collection.
     *
     * By default this just sets the collFullReviewOpinions collection to an empty array (like clearcollFullReviewOpinions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFullReviewOpinions($overrideExisting = true)
    {
        if (null !== $this->collFullReviewOpinions && !$overrideExisting) {
            return;
        }
        $this->collFullReviewOpinions = new PropelObjectCollection();
        $this->collFullReviewOpinions->setModel('FullReviewOpinion');
    }

    /**
     * Gets an array of FullReviewOpinion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|FullReviewOpinion[] List of FullReviewOpinion objects
     * @throws PropelException
     */
    public function getFullReviewOpinions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collFullReviewOpinionsPartial && !$this->isNew();
        if (null === $this->collFullReviewOpinions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFullReviewOpinions) {
                // return empty collection
                $this->initFullReviewOpinions();
            } else {
                $collFullReviewOpinions = FullReviewOpinionQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collFullReviewOpinionsPartial && count($collFullReviewOpinions)) {
                      $this->initFullReviewOpinions(false);

                      foreach ($collFullReviewOpinions as $obj) {
                        if (false == $this->collFullReviewOpinions->contains($obj)) {
                          $this->collFullReviewOpinions->append($obj);
                        }
                      }

                      $this->collFullReviewOpinionsPartial = true;
                    }

                    $collFullReviewOpinions->getInternalIterator()->rewind();

                    return $collFullReviewOpinions;
                }

                if ($partial && $this->collFullReviewOpinions) {
                    foreach ($this->collFullReviewOpinions as $obj) {
                        if ($obj->isNew()) {
                            $collFullReviewOpinions[] = $obj;
                        }
                    }
                }

                $this->collFullReviewOpinions = $collFullReviewOpinions;
                $this->collFullReviewOpinionsPartial = false;
            }
        }

        return $this->collFullReviewOpinions;
    }

    /**
     * Sets a collection of FullReviewOpinion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $fullReviewOpinions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setFullReviewOpinions(PropelCollection $fullReviewOpinions, PropelPDO $con = null)
    {
        $fullReviewOpinionsToDelete = $this->getFullReviewOpinions(new Criteria(), $con)->diff($fullReviewOpinions);


        $this->fullReviewOpinionsScheduledForDeletion = $fullReviewOpinionsToDelete;

        foreach ($fullReviewOpinionsToDelete as $fullReviewOpinionRemoved) {
            $fullReviewOpinionRemoved->setUser(null);
        }

        $this->collFullReviewOpinions = null;
        foreach ($fullReviewOpinions as $fullReviewOpinion) {
            $this->addFullReviewOpinion($fullReviewOpinion);
        }

        $this->collFullReviewOpinions = $fullReviewOpinions;
        $this->collFullReviewOpinionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related FullReviewOpinion objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related FullReviewOpinion objects.
     * @throws PropelException
     */
    public function countFullReviewOpinions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collFullReviewOpinionsPartial && !$this->isNew();
        if (null === $this->collFullReviewOpinions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFullReviewOpinions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFullReviewOpinions());
            }
            $query = FullReviewOpinionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collFullReviewOpinions);
    }

    /**
     * Method called to associate a FullReviewOpinion object to this object
     * through the FullReviewOpinion foreign key attribute.
     *
     * @param    FullReviewOpinion $l FullReviewOpinion
     * @return User The current object (for fluent API support)
     */
    public function addFullReviewOpinion(FullReviewOpinion $l)
    {
        if ($this->collFullReviewOpinions === null) {
            $this->initFullReviewOpinions();
            $this->collFullReviewOpinionsPartial = true;
        }

        if (!in_array($l, $this->collFullReviewOpinions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddFullReviewOpinion($l);

            if ($this->fullReviewOpinionsScheduledForDeletion and $this->fullReviewOpinionsScheduledForDeletion->contains($l)) {
                $this->fullReviewOpinionsScheduledForDeletion->remove($this->fullReviewOpinionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	FullReviewOpinion $fullReviewOpinion The fullReviewOpinion object to add.
     */
    protected function doAddFullReviewOpinion($fullReviewOpinion)
    {
        $this->collFullReviewOpinions[]= $fullReviewOpinion;
        $fullReviewOpinion->setUser($this);
    }

    /**
     * @param	FullReviewOpinion $fullReviewOpinion The fullReviewOpinion object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeFullReviewOpinion($fullReviewOpinion)
    {
        if ($this->getFullReviewOpinions()->contains($fullReviewOpinion)) {
            $this->collFullReviewOpinions->remove($this->collFullReviewOpinions->search($fullReviewOpinion));
            if (null === $this->fullReviewOpinionsScheduledForDeletion) {
                $this->fullReviewOpinionsScheduledForDeletion = clone $this->collFullReviewOpinions;
                $this->fullReviewOpinionsScheduledForDeletion->clear();
            }
            $this->fullReviewOpinionsScheduledForDeletion[]= clone $fullReviewOpinion;
            $fullReviewOpinion->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related FullReviewOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|FullReviewOpinion[] List of FullReviewOpinion objects
     */
    public function getFullReviewOpinionsJoinArticle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = FullReviewOpinionQuery::create(null, $criteria);
        $query->joinWith('Article', $join_behavior);

        return $this->getFullReviewOpinions($query, $con);
    }

    /**
     * Clears out the collFullReviewOpinionLogsRelatedByUserIdFrom collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addFullReviewOpinionLogsRelatedByUserIdFrom()
     */
    public function clearFullReviewOpinionLogsRelatedByUserIdFrom()
    {
        $this->collFullReviewOpinionLogsRelatedByUserIdFrom = null; // important to set this to null since that means it is uninitialized
        $this->collFullReviewOpinionLogsRelatedByUserIdFromPartial = null;

        return $this;
    }

    /**
     * reset is the collFullReviewOpinionLogsRelatedByUserIdFrom collection loaded partially
     *
     * @return void
     */
    public function resetPartialFullReviewOpinionLogsRelatedByUserIdFrom($v = true)
    {
        $this->collFullReviewOpinionLogsRelatedByUserIdFromPartial = $v;
    }

    /**
     * Initializes the collFullReviewOpinionLogsRelatedByUserIdFrom collection.
     *
     * By default this just sets the collFullReviewOpinionLogsRelatedByUserIdFrom collection to an empty array (like clearcollFullReviewOpinionLogsRelatedByUserIdFrom());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFullReviewOpinionLogsRelatedByUserIdFrom($overrideExisting = true)
    {
        if (null !== $this->collFullReviewOpinionLogsRelatedByUserIdFrom && !$overrideExisting) {
            return;
        }
        $this->collFullReviewOpinionLogsRelatedByUserIdFrom = new PropelObjectCollection();
        $this->collFullReviewOpinionLogsRelatedByUserIdFrom->setModel('FullReviewOpinionLog');
    }

    /**
     * Gets an array of FullReviewOpinionLog objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|FullReviewOpinionLog[] List of FullReviewOpinionLog objects
     * @throws PropelException
     */
    public function getFullReviewOpinionLogsRelatedByUserIdFrom($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collFullReviewOpinionLogsRelatedByUserIdFromPartial && !$this->isNew();
        if (null === $this->collFullReviewOpinionLogsRelatedByUserIdFrom || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFullReviewOpinionLogsRelatedByUserIdFrom) {
                // return empty collection
                $this->initFullReviewOpinionLogsRelatedByUserIdFrom();
            } else {
                $collFullReviewOpinionLogsRelatedByUserIdFrom = FullReviewOpinionLogQuery::create(null, $criteria)
                    ->filterByUserRelatedByUserIdFrom($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collFullReviewOpinionLogsRelatedByUserIdFromPartial && count($collFullReviewOpinionLogsRelatedByUserIdFrom)) {
                      $this->initFullReviewOpinionLogsRelatedByUserIdFrom(false);

                      foreach ($collFullReviewOpinionLogsRelatedByUserIdFrom as $obj) {
                        if (false == $this->collFullReviewOpinionLogsRelatedByUserIdFrom->contains($obj)) {
                          $this->collFullReviewOpinionLogsRelatedByUserIdFrom->append($obj);
                        }
                      }

                      $this->collFullReviewOpinionLogsRelatedByUserIdFromPartial = true;
                    }

                    $collFullReviewOpinionLogsRelatedByUserIdFrom->getInternalIterator()->rewind();

                    return $collFullReviewOpinionLogsRelatedByUserIdFrom;
                }

                if ($partial && $this->collFullReviewOpinionLogsRelatedByUserIdFrom) {
                    foreach ($this->collFullReviewOpinionLogsRelatedByUserIdFrom as $obj) {
                        if ($obj->isNew()) {
                            $collFullReviewOpinionLogsRelatedByUserIdFrom[] = $obj;
                        }
                    }
                }

                $this->collFullReviewOpinionLogsRelatedByUserIdFrom = $collFullReviewOpinionLogsRelatedByUserIdFrom;
                $this->collFullReviewOpinionLogsRelatedByUserIdFromPartial = false;
            }
        }

        return $this->collFullReviewOpinionLogsRelatedByUserIdFrom;
    }

    /**
     * Sets a collection of FullReviewOpinionLogRelatedByUserIdFrom objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $fullReviewOpinionLogsRelatedByUserIdFrom A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setFullReviewOpinionLogsRelatedByUserIdFrom(PropelCollection $fullReviewOpinionLogsRelatedByUserIdFrom, PropelPDO $con = null)
    {
        $fullReviewOpinionLogsRelatedByUserIdFromToDelete = $this->getFullReviewOpinionLogsRelatedByUserIdFrom(new Criteria(), $con)->diff($fullReviewOpinionLogsRelatedByUserIdFrom);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->fullReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion = clone $fullReviewOpinionLogsRelatedByUserIdFromToDelete;

        foreach ($fullReviewOpinionLogsRelatedByUserIdFromToDelete as $fullReviewOpinionLogRelatedByUserIdFromRemoved) {
            $fullReviewOpinionLogRelatedByUserIdFromRemoved->setUserRelatedByUserIdFrom(null);
        }

        $this->collFullReviewOpinionLogsRelatedByUserIdFrom = null;
        foreach ($fullReviewOpinionLogsRelatedByUserIdFrom as $fullReviewOpinionLogRelatedByUserIdFrom) {
            $this->addFullReviewOpinionLogRelatedByUserIdFrom($fullReviewOpinionLogRelatedByUserIdFrom);
        }

        $this->collFullReviewOpinionLogsRelatedByUserIdFrom = $fullReviewOpinionLogsRelatedByUserIdFrom;
        $this->collFullReviewOpinionLogsRelatedByUserIdFromPartial = false;

        return $this;
    }

    /**
     * Returns the number of related FullReviewOpinionLog objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related FullReviewOpinionLog objects.
     * @throws PropelException
     */
    public function countFullReviewOpinionLogsRelatedByUserIdFrom(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collFullReviewOpinionLogsRelatedByUserIdFromPartial && !$this->isNew();
        if (null === $this->collFullReviewOpinionLogsRelatedByUserIdFrom || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFullReviewOpinionLogsRelatedByUserIdFrom) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFullReviewOpinionLogsRelatedByUserIdFrom());
            }
            $query = FullReviewOpinionLogQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserRelatedByUserIdFrom($this)
                ->count($con);
        }

        return count($this->collFullReviewOpinionLogsRelatedByUserIdFrom);
    }

    /**
     * Method called to associate a FullReviewOpinionLog object to this object
     * through the FullReviewOpinionLog foreign key attribute.
     *
     * @param    FullReviewOpinionLog $l FullReviewOpinionLog
     * @return User The current object (for fluent API support)
     */
    public function addFullReviewOpinionLogRelatedByUserIdFrom(FullReviewOpinionLog $l)
    {
        if ($this->collFullReviewOpinionLogsRelatedByUserIdFrom === null) {
            $this->initFullReviewOpinionLogsRelatedByUserIdFrom();
            $this->collFullReviewOpinionLogsRelatedByUserIdFromPartial = true;
        }

        if (!in_array($l, $this->collFullReviewOpinionLogsRelatedByUserIdFrom->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddFullReviewOpinionLogRelatedByUserIdFrom($l);

            if ($this->fullReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion and $this->fullReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion->contains($l)) {
                $this->fullReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion->remove($this->fullReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	FullReviewOpinionLogRelatedByUserIdFrom $fullReviewOpinionLogRelatedByUserIdFrom The fullReviewOpinionLogRelatedByUserIdFrom object to add.
     */
    protected function doAddFullReviewOpinionLogRelatedByUserIdFrom($fullReviewOpinionLogRelatedByUserIdFrom)
    {
        $this->collFullReviewOpinionLogsRelatedByUserIdFrom[]= $fullReviewOpinionLogRelatedByUserIdFrom;
        $fullReviewOpinionLogRelatedByUserIdFrom->setUserRelatedByUserIdFrom($this);
    }

    /**
     * @param	FullReviewOpinionLogRelatedByUserIdFrom $fullReviewOpinionLogRelatedByUserIdFrom The fullReviewOpinionLogRelatedByUserIdFrom object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeFullReviewOpinionLogRelatedByUserIdFrom($fullReviewOpinionLogRelatedByUserIdFrom)
    {
        if ($this->getFullReviewOpinionLogsRelatedByUserIdFrom()->contains($fullReviewOpinionLogRelatedByUserIdFrom)) {
            $this->collFullReviewOpinionLogsRelatedByUserIdFrom->remove($this->collFullReviewOpinionLogsRelatedByUserIdFrom->search($fullReviewOpinionLogRelatedByUserIdFrom));
            if (null === $this->fullReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion) {
                $this->fullReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion = clone $this->collFullReviewOpinionLogsRelatedByUserIdFrom;
                $this->fullReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion->clear();
            }
            $this->fullReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion[]= clone $fullReviewOpinionLogRelatedByUserIdFrom;
            $fullReviewOpinionLogRelatedByUserIdFrom->setUserRelatedByUserIdFrom(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related FullReviewOpinionLogsRelatedByUserIdFrom from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|FullReviewOpinionLog[] List of FullReviewOpinionLog objects
     */
    public function getFullReviewOpinionLogsRelatedByUserIdFromJoinFullReviewOpinion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = FullReviewOpinionLogQuery::create(null, $criteria);
        $query->joinWith('FullReviewOpinion', $join_behavior);

        return $this->getFullReviewOpinionLogsRelatedByUserIdFrom($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related FullReviewOpinionLogsRelatedByUserIdFrom from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|FullReviewOpinionLog[] List of FullReviewOpinionLog objects
     */
    public function getFullReviewOpinionLogsRelatedByUserIdFromJoinArticle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = FullReviewOpinionLogQuery::create(null, $criteria);
        $query->joinWith('Article', $join_behavior);

        return $this->getFullReviewOpinionLogsRelatedByUserIdFrom($query, $con);
    }

    /**
     * Clears out the collFullReviewOpinionLogsRelatedByUserIdTo collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addFullReviewOpinionLogsRelatedByUserIdTo()
     */
    public function clearFullReviewOpinionLogsRelatedByUserIdTo()
    {
        $this->collFullReviewOpinionLogsRelatedByUserIdTo = null; // important to set this to null since that means it is uninitialized
        $this->collFullReviewOpinionLogsRelatedByUserIdToPartial = null;

        return $this;
    }

    /**
     * reset is the collFullReviewOpinionLogsRelatedByUserIdTo collection loaded partially
     *
     * @return void
     */
    public function resetPartialFullReviewOpinionLogsRelatedByUserIdTo($v = true)
    {
        $this->collFullReviewOpinionLogsRelatedByUserIdToPartial = $v;
    }

    /**
     * Initializes the collFullReviewOpinionLogsRelatedByUserIdTo collection.
     *
     * By default this just sets the collFullReviewOpinionLogsRelatedByUserIdTo collection to an empty array (like clearcollFullReviewOpinionLogsRelatedByUserIdTo());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFullReviewOpinionLogsRelatedByUserIdTo($overrideExisting = true)
    {
        if (null !== $this->collFullReviewOpinionLogsRelatedByUserIdTo && !$overrideExisting) {
            return;
        }
        $this->collFullReviewOpinionLogsRelatedByUserIdTo = new PropelObjectCollection();
        $this->collFullReviewOpinionLogsRelatedByUserIdTo->setModel('FullReviewOpinionLog');
    }

    /**
     * Gets an array of FullReviewOpinionLog objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|FullReviewOpinionLog[] List of FullReviewOpinionLog objects
     * @throws PropelException
     */
    public function getFullReviewOpinionLogsRelatedByUserIdTo($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collFullReviewOpinionLogsRelatedByUserIdToPartial && !$this->isNew();
        if (null === $this->collFullReviewOpinionLogsRelatedByUserIdTo || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFullReviewOpinionLogsRelatedByUserIdTo) {
                // return empty collection
                $this->initFullReviewOpinionLogsRelatedByUserIdTo();
            } else {
                $collFullReviewOpinionLogsRelatedByUserIdTo = FullReviewOpinionLogQuery::create(null, $criteria)
                    ->filterByUserRelatedByUserIdTo($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collFullReviewOpinionLogsRelatedByUserIdToPartial && count($collFullReviewOpinionLogsRelatedByUserIdTo)) {
                      $this->initFullReviewOpinionLogsRelatedByUserIdTo(false);

                      foreach ($collFullReviewOpinionLogsRelatedByUserIdTo as $obj) {
                        if (false == $this->collFullReviewOpinionLogsRelatedByUserIdTo->contains($obj)) {
                          $this->collFullReviewOpinionLogsRelatedByUserIdTo->append($obj);
                        }
                      }

                      $this->collFullReviewOpinionLogsRelatedByUserIdToPartial = true;
                    }

                    $collFullReviewOpinionLogsRelatedByUserIdTo->getInternalIterator()->rewind();

                    return $collFullReviewOpinionLogsRelatedByUserIdTo;
                }

                if ($partial && $this->collFullReviewOpinionLogsRelatedByUserIdTo) {
                    foreach ($this->collFullReviewOpinionLogsRelatedByUserIdTo as $obj) {
                        if ($obj->isNew()) {
                            $collFullReviewOpinionLogsRelatedByUserIdTo[] = $obj;
                        }
                    }
                }

                $this->collFullReviewOpinionLogsRelatedByUserIdTo = $collFullReviewOpinionLogsRelatedByUserIdTo;
                $this->collFullReviewOpinionLogsRelatedByUserIdToPartial = false;
            }
        }

        return $this->collFullReviewOpinionLogsRelatedByUserIdTo;
    }

    /**
     * Sets a collection of FullReviewOpinionLogRelatedByUserIdTo objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $fullReviewOpinionLogsRelatedByUserIdTo A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setFullReviewOpinionLogsRelatedByUserIdTo(PropelCollection $fullReviewOpinionLogsRelatedByUserIdTo, PropelPDO $con = null)
    {
        $fullReviewOpinionLogsRelatedByUserIdToToDelete = $this->getFullReviewOpinionLogsRelatedByUserIdTo(new Criteria(), $con)->diff($fullReviewOpinionLogsRelatedByUserIdTo);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->fullReviewOpinionLogsRelatedByUserIdToScheduledForDeletion = clone $fullReviewOpinionLogsRelatedByUserIdToToDelete;

        foreach ($fullReviewOpinionLogsRelatedByUserIdToToDelete as $fullReviewOpinionLogRelatedByUserIdToRemoved) {
            $fullReviewOpinionLogRelatedByUserIdToRemoved->setUserRelatedByUserIdTo(null);
        }

        $this->collFullReviewOpinionLogsRelatedByUserIdTo = null;
        foreach ($fullReviewOpinionLogsRelatedByUserIdTo as $fullReviewOpinionLogRelatedByUserIdTo) {
            $this->addFullReviewOpinionLogRelatedByUserIdTo($fullReviewOpinionLogRelatedByUserIdTo);
        }

        $this->collFullReviewOpinionLogsRelatedByUserIdTo = $fullReviewOpinionLogsRelatedByUserIdTo;
        $this->collFullReviewOpinionLogsRelatedByUserIdToPartial = false;

        return $this;
    }

    /**
     * Returns the number of related FullReviewOpinionLog objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related FullReviewOpinionLog objects.
     * @throws PropelException
     */
    public function countFullReviewOpinionLogsRelatedByUserIdTo(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collFullReviewOpinionLogsRelatedByUserIdToPartial && !$this->isNew();
        if (null === $this->collFullReviewOpinionLogsRelatedByUserIdTo || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFullReviewOpinionLogsRelatedByUserIdTo) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFullReviewOpinionLogsRelatedByUserIdTo());
            }
            $query = FullReviewOpinionLogQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserRelatedByUserIdTo($this)
                ->count($con);
        }

        return count($this->collFullReviewOpinionLogsRelatedByUserIdTo);
    }

    /**
     * Method called to associate a FullReviewOpinionLog object to this object
     * through the FullReviewOpinionLog foreign key attribute.
     *
     * @param    FullReviewOpinionLog $l FullReviewOpinionLog
     * @return User The current object (for fluent API support)
     */
    public function addFullReviewOpinionLogRelatedByUserIdTo(FullReviewOpinionLog $l)
    {
        if ($this->collFullReviewOpinionLogsRelatedByUserIdTo === null) {
            $this->initFullReviewOpinionLogsRelatedByUserIdTo();
            $this->collFullReviewOpinionLogsRelatedByUserIdToPartial = true;
        }

        if (!in_array($l, $this->collFullReviewOpinionLogsRelatedByUserIdTo->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddFullReviewOpinionLogRelatedByUserIdTo($l);

            if ($this->fullReviewOpinionLogsRelatedByUserIdToScheduledForDeletion and $this->fullReviewOpinionLogsRelatedByUserIdToScheduledForDeletion->contains($l)) {
                $this->fullReviewOpinionLogsRelatedByUserIdToScheduledForDeletion->remove($this->fullReviewOpinionLogsRelatedByUserIdToScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	FullReviewOpinionLogRelatedByUserIdTo $fullReviewOpinionLogRelatedByUserIdTo The fullReviewOpinionLogRelatedByUserIdTo object to add.
     */
    protected function doAddFullReviewOpinionLogRelatedByUserIdTo($fullReviewOpinionLogRelatedByUserIdTo)
    {
        $this->collFullReviewOpinionLogsRelatedByUserIdTo[]= $fullReviewOpinionLogRelatedByUserIdTo;
        $fullReviewOpinionLogRelatedByUserIdTo->setUserRelatedByUserIdTo($this);
    }

    /**
     * @param	FullReviewOpinionLogRelatedByUserIdTo $fullReviewOpinionLogRelatedByUserIdTo The fullReviewOpinionLogRelatedByUserIdTo object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeFullReviewOpinionLogRelatedByUserIdTo($fullReviewOpinionLogRelatedByUserIdTo)
    {
        if ($this->getFullReviewOpinionLogsRelatedByUserIdTo()->contains($fullReviewOpinionLogRelatedByUserIdTo)) {
            $this->collFullReviewOpinionLogsRelatedByUserIdTo->remove($this->collFullReviewOpinionLogsRelatedByUserIdTo->search($fullReviewOpinionLogRelatedByUserIdTo));
            if (null === $this->fullReviewOpinionLogsRelatedByUserIdToScheduledForDeletion) {
                $this->fullReviewOpinionLogsRelatedByUserIdToScheduledForDeletion = clone $this->collFullReviewOpinionLogsRelatedByUserIdTo;
                $this->fullReviewOpinionLogsRelatedByUserIdToScheduledForDeletion->clear();
            }
            $this->fullReviewOpinionLogsRelatedByUserIdToScheduledForDeletion[]= clone $fullReviewOpinionLogRelatedByUserIdTo;
            $fullReviewOpinionLogRelatedByUserIdTo->setUserRelatedByUserIdTo(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related FullReviewOpinionLogsRelatedByUserIdTo from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|FullReviewOpinionLog[] List of FullReviewOpinionLog objects
     */
    public function getFullReviewOpinionLogsRelatedByUserIdToJoinFullReviewOpinion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = FullReviewOpinionLogQuery::create(null, $criteria);
        $query->joinWith('FullReviewOpinion', $join_behavior);

        return $this->getFullReviewOpinionLogsRelatedByUserIdTo($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related FullReviewOpinionLogsRelatedByUserIdTo from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|FullReviewOpinionLog[] List of FullReviewOpinionLog objects
     */
    public function getFullReviewOpinionLogsRelatedByUserIdToJoinArticle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = FullReviewOpinionLogQuery::create(null, $criteria);
        $query->joinWith('Article', $join_behavior);

        return $this->getFullReviewOpinionLogsRelatedByUserIdTo($query, $con);
    }

    /**
     * Clears out the collFullReviewOpinionApprovals collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addFullReviewOpinionApprovals()
     */
    public function clearFullReviewOpinionApprovals()
    {
        $this->collFullReviewOpinionApprovals = null; // important to set this to null since that means it is uninitialized
        $this->collFullReviewOpinionApprovalsPartial = null;

        return $this;
    }

    /**
     * reset is the collFullReviewOpinionApprovals collection loaded partially
     *
     * @return void
     */
    public function resetPartialFullReviewOpinionApprovals($v = true)
    {
        $this->collFullReviewOpinionApprovalsPartial = $v;
    }

    /**
     * Initializes the collFullReviewOpinionApprovals collection.
     *
     * By default this just sets the collFullReviewOpinionApprovals collection to an empty array (like clearcollFullReviewOpinionApprovals());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFullReviewOpinionApprovals($overrideExisting = true)
    {
        if (null !== $this->collFullReviewOpinionApprovals && !$overrideExisting) {
            return;
        }
        $this->collFullReviewOpinionApprovals = new PropelObjectCollection();
        $this->collFullReviewOpinionApprovals->setModel('FullReviewOpinionApproval');
    }

    /**
     * Gets an array of FullReviewOpinionApproval objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|FullReviewOpinionApproval[] List of FullReviewOpinionApproval objects
     * @throws PropelException
     */
    public function getFullReviewOpinionApprovals($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collFullReviewOpinionApprovalsPartial && !$this->isNew();
        if (null === $this->collFullReviewOpinionApprovals || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFullReviewOpinionApprovals) {
                // return empty collection
                $this->initFullReviewOpinionApprovals();
            } else {
                $collFullReviewOpinionApprovals = FullReviewOpinionApprovalQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collFullReviewOpinionApprovalsPartial && count($collFullReviewOpinionApprovals)) {
                      $this->initFullReviewOpinionApprovals(false);

                      foreach ($collFullReviewOpinionApprovals as $obj) {
                        if (false == $this->collFullReviewOpinionApprovals->contains($obj)) {
                          $this->collFullReviewOpinionApprovals->append($obj);
                        }
                      }

                      $this->collFullReviewOpinionApprovalsPartial = true;
                    }

                    $collFullReviewOpinionApprovals->getInternalIterator()->rewind();

                    return $collFullReviewOpinionApprovals;
                }

                if ($partial && $this->collFullReviewOpinionApprovals) {
                    foreach ($this->collFullReviewOpinionApprovals as $obj) {
                        if ($obj->isNew()) {
                            $collFullReviewOpinionApprovals[] = $obj;
                        }
                    }
                }

                $this->collFullReviewOpinionApprovals = $collFullReviewOpinionApprovals;
                $this->collFullReviewOpinionApprovalsPartial = false;
            }
        }

        return $this->collFullReviewOpinionApprovals;
    }

    /**
     * Sets a collection of FullReviewOpinionApproval objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $fullReviewOpinionApprovals A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setFullReviewOpinionApprovals(PropelCollection $fullReviewOpinionApprovals, PropelPDO $con = null)
    {
        $fullReviewOpinionApprovalsToDelete = $this->getFullReviewOpinionApprovals(new Criteria(), $con)->diff($fullReviewOpinionApprovals);


        $this->fullReviewOpinionApprovalsScheduledForDeletion = $fullReviewOpinionApprovalsToDelete;

        foreach ($fullReviewOpinionApprovalsToDelete as $fullReviewOpinionApprovalRemoved) {
            $fullReviewOpinionApprovalRemoved->setUser(null);
        }

        $this->collFullReviewOpinionApprovals = null;
        foreach ($fullReviewOpinionApprovals as $fullReviewOpinionApproval) {
            $this->addFullReviewOpinionApproval($fullReviewOpinionApproval);
        }

        $this->collFullReviewOpinionApprovals = $fullReviewOpinionApprovals;
        $this->collFullReviewOpinionApprovalsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related FullReviewOpinionApproval objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related FullReviewOpinionApproval objects.
     * @throws PropelException
     */
    public function countFullReviewOpinionApprovals(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collFullReviewOpinionApprovalsPartial && !$this->isNew();
        if (null === $this->collFullReviewOpinionApprovals || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFullReviewOpinionApprovals) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFullReviewOpinionApprovals());
            }
            $query = FullReviewOpinionApprovalQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collFullReviewOpinionApprovals);
    }

    /**
     * Method called to associate a FullReviewOpinionApproval object to this object
     * through the FullReviewOpinionApproval foreign key attribute.
     *
     * @param    FullReviewOpinionApproval $l FullReviewOpinionApproval
     * @return User The current object (for fluent API support)
     */
    public function addFullReviewOpinionApproval(FullReviewOpinionApproval $l)
    {
        if ($this->collFullReviewOpinionApprovals === null) {
            $this->initFullReviewOpinionApprovals();
            $this->collFullReviewOpinionApprovalsPartial = true;
        }

        if (!in_array($l, $this->collFullReviewOpinionApprovals->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddFullReviewOpinionApproval($l);

            if ($this->fullReviewOpinionApprovalsScheduledForDeletion and $this->fullReviewOpinionApprovalsScheduledForDeletion->contains($l)) {
                $this->fullReviewOpinionApprovalsScheduledForDeletion->remove($this->fullReviewOpinionApprovalsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	FullReviewOpinionApproval $fullReviewOpinionApproval The fullReviewOpinionApproval object to add.
     */
    protected function doAddFullReviewOpinionApproval($fullReviewOpinionApproval)
    {
        $this->collFullReviewOpinionApprovals[]= $fullReviewOpinionApproval;
        $fullReviewOpinionApproval->setUser($this);
    }

    /**
     * @param	FullReviewOpinionApproval $fullReviewOpinionApproval The fullReviewOpinionApproval object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeFullReviewOpinionApproval($fullReviewOpinionApproval)
    {
        if ($this->getFullReviewOpinionApprovals()->contains($fullReviewOpinionApproval)) {
            $this->collFullReviewOpinionApprovals->remove($this->collFullReviewOpinionApprovals->search($fullReviewOpinionApproval));
            if (null === $this->fullReviewOpinionApprovalsScheduledForDeletion) {
                $this->fullReviewOpinionApprovalsScheduledForDeletion = clone $this->collFullReviewOpinionApprovals;
                $this->fullReviewOpinionApprovalsScheduledForDeletion->clear();
            }
            $this->fullReviewOpinionApprovalsScheduledForDeletion[]= clone $fullReviewOpinionApproval;
            $fullReviewOpinionApproval->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related FullReviewOpinionApprovals from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|FullReviewOpinionApproval[] List of FullReviewOpinionApproval objects
     */
    public function getFullReviewOpinionApprovalsJoinFullReviewOpinion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = FullReviewOpinionApprovalQuery::create(null, $criteria);
        $query->joinWith('FullReviewOpinion', $join_behavior);

        return $this->getFullReviewOpinionApprovals($query, $con);
    }

    /**
     * Clears out the collGroupOpinions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addGroupOpinions()
     */
    public function clearGroupOpinions()
    {
        $this->collGroupOpinions = null; // important to set this to null since that means it is uninitialized
        $this->collGroupOpinionsPartial = null;

        return $this;
    }

    /**
     * reset is the collGroupOpinions collection loaded partially
     *
     * @return void
     */
    public function resetPartialGroupOpinions($v = true)
    {
        $this->collGroupOpinionsPartial = $v;
    }

    /**
     * Initializes the collGroupOpinions collection.
     *
     * By default this just sets the collGroupOpinions collection to an empty array (like clearcollGroupOpinions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGroupOpinions($overrideExisting = true)
    {
        if (null !== $this->collGroupOpinions && !$overrideExisting) {
            return;
        }
        $this->collGroupOpinions = new PropelObjectCollection();
        $this->collGroupOpinions->setModel('GroupOpinion');
    }

    /**
     * Gets an array of GroupOpinion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|GroupOpinion[] List of GroupOpinion objects
     * @throws PropelException
     */
    public function getGroupOpinions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collGroupOpinionsPartial && !$this->isNew();
        if (null === $this->collGroupOpinions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGroupOpinions) {
                // return empty collection
                $this->initGroupOpinions();
            } else {
                $collGroupOpinions = GroupOpinionQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collGroupOpinionsPartial && count($collGroupOpinions)) {
                      $this->initGroupOpinions(false);

                      foreach ($collGroupOpinions as $obj) {
                        if (false == $this->collGroupOpinions->contains($obj)) {
                          $this->collGroupOpinions->append($obj);
                        }
                      }

                      $this->collGroupOpinionsPartial = true;
                    }

                    $collGroupOpinions->getInternalIterator()->rewind();

                    return $collGroupOpinions;
                }

                if ($partial && $this->collGroupOpinions) {
                    foreach ($this->collGroupOpinions as $obj) {
                        if ($obj->isNew()) {
                            $collGroupOpinions[] = $obj;
                        }
                    }
                }

                $this->collGroupOpinions = $collGroupOpinions;
                $this->collGroupOpinionsPartial = false;
            }
        }

        return $this->collGroupOpinions;
    }

    /**
     * Sets a collection of GroupOpinion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $groupOpinions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setGroupOpinions(PropelCollection $groupOpinions, PropelPDO $con = null)
    {
        $groupOpinionsToDelete = $this->getGroupOpinions(new Criteria(), $con)->diff($groupOpinions);


        $this->groupOpinionsScheduledForDeletion = $groupOpinionsToDelete;

        foreach ($groupOpinionsToDelete as $groupOpinionRemoved) {
            $groupOpinionRemoved->setUser(null);
        }

        $this->collGroupOpinions = null;
        foreach ($groupOpinions as $groupOpinion) {
            $this->addGroupOpinion($groupOpinion);
        }

        $this->collGroupOpinions = $groupOpinions;
        $this->collGroupOpinionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related GroupOpinion objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related GroupOpinion objects.
     * @throws PropelException
     */
    public function countGroupOpinions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collGroupOpinionsPartial && !$this->isNew();
        if (null === $this->collGroupOpinions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGroupOpinions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getGroupOpinions());
            }
            $query = GroupOpinionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collGroupOpinions);
    }

    /**
     * Method called to associate a GroupOpinion object to this object
     * through the GroupOpinion foreign key attribute.
     *
     * @param    GroupOpinion $l GroupOpinion
     * @return User The current object (for fluent API support)
     */
    public function addGroupOpinion(GroupOpinion $l)
    {
        if ($this->collGroupOpinions === null) {
            $this->initGroupOpinions();
            $this->collGroupOpinionsPartial = true;
        }

        if (!in_array($l, $this->collGroupOpinions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddGroupOpinion($l);

            if ($this->groupOpinionsScheduledForDeletion and $this->groupOpinionsScheduledForDeletion->contains($l)) {
                $this->groupOpinionsScheduledForDeletion->remove($this->groupOpinionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	GroupOpinion $groupOpinion The groupOpinion object to add.
     */
    protected function doAddGroupOpinion($groupOpinion)
    {
        $this->collGroupOpinions[]= $groupOpinion;
        $groupOpinion->setUser($this);
    }

    /**
     * @param	GroupOpinion $groupOpinion The groupOpinion object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeGroupOpinion($groupOpinion)
    {
        if ($this->getGroupOpinions()->contains($groupOpinion)) {
            $this->collGroupOpinions->remove($this->collGroupOpinions->search($groupOpinion));
            if (null === $this->groupOpinionsScheduledForDeletion) {
                $this->groupOpinionsScheduledForDeletion = clone $this->collGroupOpinions;
                $this->groupOpinionsScheduledForDeletion->clear();
            }
            $this->groupOpinionsScheduledForDeletion[]= $groupOpinion;
            $groupOpinion->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related GroupOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|GroupOpinion[] List of GroupOpinion objects
     */
    public function getGroupOpinionsJoinGroupGenderType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = GroupOpinionQuery::create(null, $criteria);
        $query->joinWith('GroupGenderType', $join_behavior);

        return $this->getGroupOpinions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related GroupOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|GroupOpinion[] List of GroupOpinion objects
     */
    public function getGroupOpinionsJoinArticle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = GroupOpinionQuery::create(null, $criteria);
        $query->joinWith('Article', $join_behavior);

        return $this->getGroupOpinions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related GroupOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|GroupOpinion[] List of GroupOpinion objects
     */
    public function getGroupOpinionsJoinGroupOpinionRelatedBySubGroupOpinionId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = GroupOpinionQuery::create(null, $criteria);
        $query->joinWith('GroupOpinionRelatedBySubGroupOpinionId', $join_behavior);

        return $this->getGroupOpinions($query, $con);
    }

    /**
     * Clears out the collInitialReviewOpinions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addInitialReviewOpinions()
     */
    public function clearInitialReviewOpinions()
    {
        $this->collInitialReviewOpinions = null; // important to set this to null since that means it is uninitialized
        $this->collInitialReviewOpinionsPartial = null;

        return $this;
    }

    /**
     * reset is the collInitialReviewOpinions collection loaded partially
     *
     * @return void
     */
    public function resetPartialInitialReviewOpinions($v = true)
    {
        $this->collInitialReviewOpinionsPartial = $v;
    }

    /**
     * Initializes the collInitialReviewOpinions collection.
     *
     * By default this just sets the collInitialReviewOpinions collection to an empty array (like clearcollInitialReviewOpinions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInitialReviewOpinions($overrideExisting = true)
    {
        if (null !== $this->collInitialReviewOpinions && !$overrideExisting) {
            return;
        }
        $this->collInitialReviewOpinions = new PropelObjectCollection();
        $this->collInitialReviewOpinions->setModel('InitialReviewOpinion');
    }

    /**
     * Gets an array of InitialReviewOpinion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|InitialReviewOpinion[] List of InitialReviewOpinion objects
     * @throws PropelException
     */
    public function getInitialReviewOpinions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collInitialReviewOpinionsPartial && !$this->isNew();
        if (null === $this->collInitialReviewOpinions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collInitialReviewOpinions) {
                // return empty collection
                $this->initInitialReviewOpinions();
            } else {
                $collInitialReviewOpinions = InitialReviewOpinionQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collInitialReviewOpinionsPartial && count($collInitialReviewOpinions)) {
                      $this->initInitialReviewOpinions(false);

                      foreach ($collInitialReviewOpinions as $obj) {
                        if (false == $this->collInitialReviewOpinions->contains($obj)) {
                          $this->collInitialReviewOpinions->append($obj);
                        }
                      }

                      $this->collInitialReviewOpinionsPartial = true;
                    }

                    $collInitialReviewOpinions->getInternalIterator()->rewind();

                    return $collInitialReviewOpinions;
                }

                if ($partial && $this->collInitialReviewOpinions) {
                    foreach ($this->collInitialReviewOpinions as $obj) {
                        if ($obj->isNew()) {
                            $collInitialReviewOpinions[] = $obj;
                        }
                    }
                }

                $this->collInitialReviewOpinions = $collInitialReviewOpinions;
                $this->collInitialReviewOpinionsPartial = false;
            }
        }

        return $this->collInitialReviewOpinions;
    }

    /**
     * Sets a collection of InitialReviewOpinion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $initialReviewOpinions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setInitialReviewOpinions(PropelCollection $initialReviewOpinions, PropelPDO $con = null)
    {
        $initialReviewOpinionsToDelete = $this->getInitialReviewOpinions(new Criteria(), $con)->diff($initialReviewOpinions);


        $this->initialReviewOpinionsScheduledForDeletion = $initialReviewOpinionsToDelete;

        foreach ($initialReviewOpinionsToDelete as $initialReviewOpinionRemoved) {
            $initialReviewOpinionRemoved->setUser(null);
        }

        $this->collInitialReviewOpinions = null;
        foreach ($initialReviewOpinions as $initialReviewOpinion) {
            $this->addInitialReviewOpinion($initialReviewOpinion);
        }

        $this->collInitialReviewOpinions = $initialReviewOpinions;
        $this->collInitialReviewOpinionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related InitialReviewOpinion objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related InitialReviewOpinion objects.
     * @throws PropelException
     */
    public function countInitialReviewOpinions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collInitialReviewOpinionsPartial && !$this->isNew();
        if (null === $this->collInitialReviewOpinions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInitialReviewOpinions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInitialReviewOpinions());
            }
            $query = InitialReviewOpinionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collInitialReviewOpinions);
    }

    /**
     * Method called to associate a InitialReviewOpinion object to this object
     * through the InitialReviewOpinion foreign key attribute.
     *
     * @param    InitialReviewOpinion $l InitialReviewOpinion
     * @return User The current object (for fluent API support)
     */
    public function addInitialReviewOpinion(InitialReviewOpinion $l)
    {
        if ($this->collInitialReviewOpinions === null) {
            $this->initInitialReviewOpinions();
            $this->collInitialReviewOpinionsPartial = true;
        }

        if (!in_array($l, $this->collInitialReviewOpinions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddInitialReviewOpinion($l);

            if ($this->initialReviewOpinionsScheduledForDeletion and $this->initialReviewOpinionsScheduledForDeletion->contains($l)) {
                $this->initialReviewOpinionsScheduledForDeletion->remove($this->initialReviewOpinionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	InitialReviewOpinion $initialReviewOpinion The initialReviewOpinion object to add.
     */
    protected function doAddInitialReviewOpinion($initialReviewOpinion)
    {
        $this->collInitialReviewOpinions[]= $initialReviewOpinion;
        $initialReviewOpinion->setUser($this);
    }

    /**
     * @param	InitialReviewOpinion $initialReviewOpinion The initialReviewOpinion object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeInitialReviewOpinion($initialReviewOpinion)
    {
        if ($this->getInitialReviewOpinions()->contains($initialReviewOpinion)) {
            $this->collInitialReviewOpinions->remove($this->collInitialReviewOpinions->search($initialReviewOpinion));
            if (null === $this->initialReviewOpinionsScheduledForDeletion) {
                $this->initialReviewOpinionsScheduledForDeletion = clone $this->collInitialReviewOpinions;
                $this->initialReviewOpinionsScheduledForDeletion->clear();
            }
            $this->initialReviewOpinionsScheduledForDeletion[]= clone $initialReviewOpinion;
            $initialReviewOpinion->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related InitialReviewOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|InitialReviewOpinion[] List of InitialReviewOpinion objects
     */
    public function getInitialReviewOpinionsJoinArticle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = InitialReviewOpinionQuery::create(null, $criteria);
        $query->joinWith('Article', $join_behavior);

        return $this->getInitialReviewOpinions($query, $con);
    }

    /**
     * Clears out the collInitialReviewOpinionLogsRelatedByUserIdFrom collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addInitialReviewOpinionLogsRelatedByUserIdFrom()
     */
    public function clearInitialReviewOpinionLogsRelatedByUserIdFrom()
    {
        $this->collInitialReviewOpinionLogsRelatedByUserIdFrom = null; // important to set this to null since that means it is uninitialized
        $this->collInitialReviewOpinionLogsRelatedByUserIdFromPartial = null;

        return $this;
    }

    /**
     * reset is the collInitialReviewOpinionLogsRelatedByUserIdFrom collection loaded partially
     *
     * @return void
     */
    public function resetPartialInitialReviewOpinionLogsRelatedByUserIdFrom($v = true)
    {
        $this->collInitialReviewOpinionLogsRelatedByUserIdFromPartial = $v;
    }

    /**
     * Initializes the collInitialReviewOpinionLogsRelatedByUserIdFrom collection.
     *
     * By default this just sets the collInitialReviewOpinionLogsRelatedByUserIdFrom collection to an empty array (like clearcollInitialReviewOpinionLogsRelatedByUserIdFrom());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInitialReviewOpinionLogsRelatedByUserIdFrom($overrideExisting = true)
    {
        if (null !== $this->collInitialReviewOpinionLogsRelatedByUserIdFrom && !$overrideExisting) {
            return;
        }
        $this->collInitialReviewOpinionLogsRelatedByUserIdFrom = new PropelObjectCollection();
        $this->collInitialReviewOpinionLogsRelatedByUserIdFrom->setModel('InitialReviewOpinionLog');
    }

    /**
     * Gets an array of InitialReviewOpinionLog objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|InitialReviewOpinionLog[] List of InitialReviewOpinionLog objects
     * @throws PropelException
     */
    public function getInitialReviewOpinionLogsRelatedByUserIdFrom($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collInitialReviewOpinionLogsRelatedByUserIdFromPartial && !$this->isNew();
        if (null === $this->collInitialReviewOpinionLogsRelatedByUserIdFrom || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collInitialReviewOpinionLogsRelatedByUserIdFrom) {
                // return empty collection
                $this->initInitialReviewOpinionLogsRelatedByUserIdFrom();
            } else {
                $collInitialReviewOpinionLogsRelatedByUserIdFrom = InitialReviewOpinionLogQuery::create(null, $criteria)
                    ->filterByUserRelatedByUserIdFrom($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collInitialReviewOpinionLogsRelatedByUserIdFromPartial && count($collInitialReviewOpinionLogsRelatedByUserIdFrom)) {
                      $this->initInitialReviewOpinionLogsRelatedByUserIdFrom(false);

                      foreach ($collInitialReviewOpinionLogsRelatedByUserIdFrom as $obj) {
                        if (false == $this->collInitialReviewOpinionLogsRelatedByUserIdFrom->contains($obj)) {
                          $this->collInitialReviewOpinionLogsRelatedByUserIdFrom->append($obj);
                        }
                      }

                      $this->collInitialReviewOpinionLogsRelatedByUserIdFromPartial = true;
                    }

                    $collInitialReviewOpinionLogsRelatedByUserIdFrom->getInternalIterator()->rewind();

                    return $collInitialReviewOpinionLogsRelatedByUserIdFrom;
                }

                if ($partial && $this->collInitialReviewOpinionLogsRelatedByUserIdFrom) {
                    foreach ($this->collInitialReviewOpinionLogsRelatedByUserIdFrom as $obj) {
                        if ($obj->isNew()) {
                            $collInitialReviewOpinionLogsRelatedByUserIdFrom[] = $obj;
                        }
                    }
                }

                $this->collInitialReviewOpinionLogsRelatedByUserIdFrom = $collInitialReviewOpinionLogsRelatedByUserIdFrom;
                $this->collInitialReviewOpinionLogsRelatedByUserIdFromPartial = false;
            }
        }

        return $this->collInitialReviewOpinionLogsRelatedByUserIdFrom;
    }

    /**
     * Sets a collection of InitialReviewOpinionLogRelatedByUserIdFrom objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $initialReviewOpinionLogsRelatedByUserIdFrom A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setInitialReviewOpinionLogsRelatedByUserIdFrom(PropelCollection $initialReviewOpinionLogsRelatedByUserIdFrom, PropelPDO $con = null)
    {
        $initialReviewOpinionLogsRelatedByUserIdFromToDelete = $this->getInitialReviewOpinionLogsRelatedByUserIdFrom(new Criteria(), $con)->diff($initialReviewOpinionLogsRelatedByUserIdFrom);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->initialReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion = clone $initialReviewOpinionLogsRelatedByUserIdFromToDelete;

        foreach ($initialReviewOpinionLogsRelatedByUserIdFromToDelete as $initialReviewOpinionLogRelatedByUserIdFromRemoved) {
            $initialReviewOpinionLogRelatedByUserIdFromRemoved->setUserRelatedByUserIdFrom(null);
        }

        $this->collInitialReviewOpinionLogsRelatedByUserIdFrom = null;
        foreach ($initialReviewOpinionLogsRelatedByUserIdFrom as $initialReviewOpinionLogRelatedByUserIdFrom) {
            $this->addInitialReviewOpinionLogRelatedByUserIdFrom($initialReviewOpinionLogRelatedByUserIdFrom);
        }

        $this->collInitialReviewOpinionLogsRelatedByUserIdFrom = $initialReviewOpinionLogsRelatedByUserIdFrom;
        $this->collInitialReviewOpinionLogsRelatedByUserIdFromPartial = false;

        return $this;
    }

    /**
     * Returns the number of related InitialReviewOpinionLog objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related InitialReviewOpinionLog objects.
     * @throws PropelException
     */
    public function countInitialReviewOpinionLogsRelatedByUserIdFrom(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collInitialReviewOpinionLogsRelatedByUserIdFromPartial && !$this->isNew();
        if (null === $this->collInitialReviewOpinionLogsRelatedByUserIdFrom || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInitialReviewOpinionLogsRelatedByUserIdFrom) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInitialReviewOpinionLogsRelatedByUserIdFrom());
            }
            $query = InitialReviewOpinionLogQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserRelatedByUserIdFrom($this)
                ->count($con);
        }

        return count($this->collInitialReviewOpinionLogsRelatedByUserIdFrom);
    }

    /**
     * Method called to associate a InitialReviewOpinionLog object to this object
     * through the InitialReviewOpinionLog foreign key attribute.
     *
     * @param    InitialReviewOpinionLog $l InitialReviewOpinionLog
     * @return User The current object (for fluent API support)
     */
    public function addInitialReviewOpinionLogRelatedByUserIdFrom(InitialReviewOpinionLog $l)
    {
        if ($this->collInitialReviewOpinionLogsRelatedByUserIdFrom === null) {
            $this->initInitialReviewOpinionLogsRelatedByUserIdFrom();
            $this->collInitialReviewOpinionLogsRelatedByUserIdFromPartial = true;
        }

        if (!in_array($l, $this->collInitialReviewOpinionLogsRelatedByUserIdFrom->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddInitialReviewOpinionLogRelatedByUserIdFrom($l);

            if ($this->initialReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion and $this->initialReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion->contains($l)) {
                $this->initialReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion->remove($this->initialReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	InitialReviewOpinionLogRelatedByUserIdFrom $initialReviewOpinionLogRelatedByUserIdFrom The initialReviewOpinionLogRelatedByUserIdFrom object to add.
     */
    protected function doAddInitialReviewOpinionLogRelatedByUserIdFrom($initialReviewOpinionLogRelatedByUserIdFrom)
    {
        $this->collInitialReviewOpinionLogsRelatedByUserIdFrom[]= $initialReviewOpinionLogRelatedByUserIdFrom;
        $initialReviewOpinionLogRelatedByUserIdFrom->setUserRelatedByUserIdFrom($this);
    }

    /**
     * @param	InitialReviewOpinionLogRelatedByUserIdFrom $initialReviewOpinionLogRelatedByUserIdFrom The initialReviewOpinionLogRelatedByUserIdFrom object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeInitialReviewOpinionLogRelatedByUserIdFrom($initialReviewOpinionLogRelatedByUserIdFrom)
    {
        if ($this->getInitialReviewOpinionLogsRelatedByUserIdFrom()->contains($initialReviewOpinionLogRelatedByUserIdFrom)) {
            $this->collInitialReviewOpinionLogsRelatedByUserIdFrom->remove($this->collInitialReviewOpinionLogsRelatedByUserIdFrom->search($initialReviewOpinionLogRelatedByUserIdFrom));
            if (null === $this->initialReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion) {
                $this->initialReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion = clone $this->collInitialReviewOpinionLogsRelatedByUserIdFrom;
                $this->initialReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion->clear();
            }
            $this->initialReviewOpinionLogsRelatedByUserIdFromScheduledForDeletion[]= clone $initialReviewOpinionLogRelatedByUserIdFrom;
            $initialReviewOpinionLogRelatedByUserIdFrom->setUserRelatedByUserIdFrom(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related InitialReviewOpinionLogsRelatedByUserIdFrom from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|InitialReviewOpinionLog[] List of InitialReviewOpinionLog objects
     */
    public function getInitialReviewOpinionLogsRelatedByUserIdFromJoinInitialReviewOpinion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = InitialReviewOpinionLogQuery::create(null, $criteria);
        $query->joinWith('InitialReviewOpinion', $join_behavior);

        return $this->getInitialReviewOpinionLogsRelatedByUserIdFrom($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related InitialReviewOpinionLogsRelatedByUserIdFrom from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|InitialReviewOpinionLog[] List of InitialReviewOpinionLog objects
     */
    public function getInitialReviewOpinionLogsRelatedByUserIdFromJoinArticle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = InitialReviewOpinionLogQuery::create(null, $criteria);
        $query->joinWith('Article', $join_behavior);

        return $this->getInitialReviewOpinionLogsRelatedByUserIdFrom($query, $con);
    }

    /**
     * Clears out the collInitialReviewOpinionLogsRelatedByUserIdTo collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addInitialReviewOpinionLogsRelatedByUserIdTo()
     */
    public function clearInitialReviewOpinionLogsRelatedByUserIdTo()
    {
        $this->collInitialReviewOpinionLogsRelatedByUserIdTo = null; // important to set this to null since that means it is uninitialized
        $this->collInitialReviewOpinionLogsRelatedByUserIdToPartial = null;

        return $this;
    }

    /**
     * reset is the collInitialReviewOpinionLogsRelatedByUserIdTo collection loaded partially
     *
     * @return void
     */
    public function resetPartialInitialReviewOpinionLogsRelatedByUserIdTo($v = true)
    {
        $this->collInitialReviewOpinionLogsRelatedByUserIdToPartial = $v;
    }

    /**
     * Initializes the collInitialReviewOpinionLogsRelatedByUserIdTo collection.
     *
     * By default this just sets the collInitialReviewOpinionLogsRelatedByUserIdTo collection to an empty array (like clearcollInitialReviewOpinionLogsRelatedByUserIdTo());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInitialReviewOpinionLogsRelatedByUserIdTo($overrideExisting = true)
    {
        if (null !== $this->collInitialReviewOpinionLogsRelatedByUserIdTo && !$overrideExisting) {
            return;
        }
        $this->collInitialReviewOpinionLogsRelatedByUserIdTo = new PropelObjectCollection();
        $this->collInitialReviewOpinionLogsRelatedByUserIdTo->setModel('InitialReviewOpinionLog');
    }

    /**
     * Gets an array of InitialReviewOpinionLog objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|InitialReviewOpinionLog[] List of InitialReviewOpinionLog objects
     * @throws PropelException
     */
    public function getInitialReviewOpinionLogsRelatedByUserIdTo($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collInitialReviewOpinionLogsRelatedByUserIdToPartial && !$this->isNew();
        if (null === $this->collInitialReviewOpinionLogsRelatedByUserIdTo || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collInitialReviewOpinionLogsRelatedByUserIdTo) {
                // return empty collection
                $this->initInitialReviewOpinionLogsRelatedByUserIdTo();
            } else {
                $collInitialReviewOpinionLogsRelatedByUserIdTo = InitialReviewOpinionLogQuery::create(null, $criteria)
                    ->filterByUserRelatedByUserIdTo($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collInitialReviewOpinionLogsRelatedByUserIdToPartial && count($collInitialReviewOpinionLogsRelatedByUserIdTo)) {
                      $this->initInitialReviewOpinionLogsRelatedByUserIdTo(false);

                      foreach ($collInitialReviewOpinionLogsRelatedByUserIdTo as $obj) {
                        if (false == $this->collInitialReviewOpinionLogsRelatedByUserIdTo->contains($obj)) {
                          $this->collInitialReviewOpinionLogsRelatedByUserIdTo->append($obj);
                        }
                      }

                      $this->collInitialReviewOpinionLogsRelatedByUserIdToPartial = true;
                    }

                    $collInitialReviewOpinionLogsRelatedByUserIdTo->getInternalIterator()->rewind();

                    return $collInitialReviewOpinionLogsRelatedByUserIdTo;
                }

                if ($partial && $this->collInitialReviewOpinionLogsRelatedByUserIdTo) {
                    foreach ($this->collInitialReviewOpinionLogsRelatedByUserIdTo as $obj) {
                        if ($obj->isNew()) {
                            $collInitialReviewOpinionLogsRelatedByUserIdTo[] = $obj;
                        }
                    }
                }

                $this->collInitialReviewOpinionLogsRelatedByUserIdTo = $collInitialReviewOpinionLogsRelatedByUserIdTo;
                $this->collInitialReviewOpinionLogsRelatedByUserIdToPartial = false;
            }
        }

        return $this->collInitialReviewOpinionLogsRelatedByUserIdTo;
    }

    /**
     * Sets a collection of InitialReviewOpinionLogRelatedByUserIdTo objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $initialReviewOpinionLogsRelatedByUserIdTo A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setInitialReviewOpinionLogsRelatedByUserIdTo(PropelCollection $initialReviewOpinionLogsRelatedByUserIdTo, PropelPDO $con = null)
    {
        $initialReviewOpinionLogsRelatedByUserIdToToDelete = $this->getInitialReviewOpinionLogsRelatedByUserIdTo(new Criteria(), $con)->diff($initialReviewOpinionLogsRelatedByUserIdTo);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->initialReviewOpinionLogsRelatedByUserIdToScheduledForDeletion = clone $initialReviewOpinionLogsRelatedByUserIdToToDelete;

        foreach ($initialReviewOpinionLogsRelatedByUserIdToToDelete as $initialReviewOpinionLogRelatedByUserIdToRemoved) {
            $initialReviewOpinionLogRelatedByUserIdToRemoved->setUserRelatedByUserIdTo(null);
        }

        $this->collInitialReviewOpinionLogsRelatedByUserIdTo = null;
        foreach ($initialReviewOpinionLogsRelatedByUserIdTo as $initialReviewOpinionLogRelatedByUserIdTo) {
            $this->addInitialReviewOpinionLogRelatedByUserIdTo($initialReviewOpinionLogRelatedByUserIdTo);
        }

        $this->collInitialReviewOpinionLogsRelatedByUserIdTo = $initialReviewOpinionLogsRelatedByUserIdTo;
        $this->collInitialReviewOpinionLogsRelatedByUserIdToPartial = false;

        return $this;
    }

    /**
     * Returns the number of related InitialReviewOpinionLog objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related InitialReviewOpinionLog objects.
     * @throws PropelException
     */
    public function countInitialReviewOpinionLogsRelatedByUserIdTo(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collInitialReviewOpinionLogsRelatedByUserIdToPartial && !$this->isNew();
        if (null === $this->collInitialReviewOpinionLogsRelatedByUserIdTo || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInitialReviewOpinionLogsRelatedByUserIdTo) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInitialReviewOpinionLogsRelatedByUserIdTo());
            }
            $query = InitialReviewOpinionLogQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserRelatedByUserIdTo($this)
                ->count($con);
        }

        return count($this->collInitialReviewOpinionLogsRelatedByUserIdTo);
    }

    /**
     * Method called to associate a InitialReviewOpinionLog object to this object
     * through the InitialReviewOpinionLog foreign key attribute.
     *
     * @param    InitialReviewOpinionLog $l InitialReviewOpinionLog
     * @return User The current object (for fluent API support)
     */
    public function addInitialReviewOpinionLogRelatedByUserIdTo(InitialReviewOpinionLog $l)
    {
        if ($this->collInitialReviewOpinionLogsRelatedByUserIdTo === null) {
            $this->initInitialReviewOpinionLogsRelatedByUserIdTo();
            $this->collInitialReviewOpinionLogsRelatedByUserIdToPartial = true;
        }

        if (!in_array($l, $this->collInitialReviewOpinionLogsRelatedByUserIdTo->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddInitialReviewOpinionLogRelatedByUserIdTo($l);

            if ($this->initialReviewOpinionLogsRelatedByUserIdToScheduledForDeletion and $this->initialReviewOpinionLogsRelatedByUserIdToScheduledForDeletion->contains($l)) {
                $this->initialReviewOpinionLogsRelatedByUserIdToScheduledForDeletion->remove($this->initialReviewOpinionLogsRelatedByUserIdToScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	InitialReviewOpinionLogRelatedByUserIdTo $initialReviewOpinionLogRelatedByUserIdTo The initialReviewOpinionLogRelatedByUserIdTo object to add.
     */
    protected function doAddInitialReviewOpinionLogRelatedByUserIdTo($initialReviewOpinionLogRelatedByUserIdTo)
    {
        $this->collInitialReviewOpinionLogsRelatedByUserIdTo[]= $initialReviewOpinionLogRelatedByUserIdTo;
        $initialReviewOpinionLogRelatedByUserIdTo->setUserRelatedByUserIdTo($this);
    }

    /**
     * @param	InitialReviewOpinionLogRelatedByUserIdTo $initialReviewOpinionLogRelatedByUserIdTo The initialReviewOpinionLogRelatedByUserIdTo object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeInitialReviewOpinionLogRelatedByUserIdTo($initialReviewOpinionLogRelatedByUserIdTo)
    {
        if ($this->getInitialReviewOpinionLogsRelatedByUserIdTo()->contains($initialReviewOpinionLogRelatedByUserIdTo)) {
            $this->collInitialReviewOpinionLogsRelatedByUserIdTo->remove($this->collInitialReviewOpinionLogsRelatedByUserIdTo->search($initialReviewOpinionLogRelatedByUserIdTo));
            if (null === $this->initialReviewOpinionLogsRelatedByUserIdToScheduledForDeletion) {
                $this->initialReviewOpinionLogsRelatedByUserIdToScheduledForDeletion = clone $this->collInitialReviewOpinionLogsRelatedByUserIdTo;
                $this->initialReviewOpinionLogsRelatedByUserIdToScheduledForDeletion->clear();
            }
            $this->initialReviewOpinionLogsRelatedByUserIdToScheduledForDeletion[]= clone $initialReviewOpinionLogRelatedByUserIdTo;
            $initialReviewOpinionLogRelatedByUserIdTo->setUserRelatedByUserIdTo(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related InitialReviewOpinionLogsRelatedByUserIdTo from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|InitialReviewOpinionLog[] List of InitialReviewOpinionLog objects
     */
    public function getInitialReviewOpinionLogsRelatedByUserIdToJoinInitialReviewOpinion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = InitialReviewOpinionLogQuery::create(null, $criteria);
        $query->joinWith('InitialReviewOpinion', $join_behavior);

        return $this->getInitialReviewOpinionLogsRelatedByUserIdTo($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related InitialReviewOpinionLogsRelatedByUserIdTo from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|InitialReviewOpinionLog[] List of InitialReviewOpinionLog objects
     */
    public function getInitialReviewOpinionLogsRelatedByUserIdToJoinArticle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = InitialReviewOpinionLogQuery::create(null, $criteria);
        $query->joinWith('Article', $join_behavior);

        return $this->getInitialReviewOpinionLogsRelatedByUserIdTo($query, $con);
    }

    /**
     * Clears out the collMeasurementOpinions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addMeasurementOpinions()
     */
    public function clearMeasurementOpinions()
    {
        $this->collMeasurementOpinions = null; // important to set this to null since that means it is uninitialized
        $this->collMeasurementOpinionsPartial = null;

        return $this;
    }

    /**
     * reset is the collMeasurementOpinions collection loaded partially
     *
     * @return void
     */
    public function resetPartialMeasurementOpinions($v = true)
    {
        $this->collMeasurementOpinionsPartial = $v;
    }

    /**
     * Initializes the collMeasurementOpinions collection.
     *
     * By default this just sets the collMeasurementOpinions collection to an empty array (like clearcollMeasurementOpinions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMeasurementOpinions($overrideExisting = true)
    {
        if (null !== $this->collMeasurementOpinions && !$overrideExisting) {
            return;
        }
        $this->collMeasurementOpinions = new PropelObjectCollection();
        $this->collMeasurementOpinions->setModel('MeasurementOpinion');
    }

    /**
     * Gets an array of MeasurementOpinion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|MeasurementOpinion[] List of MeasurementOpinion objects
     * @throws PropelException
     */
    public function getMeasurementOpinions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collMeasurementOpinionsPartial && !$this->isNew();
        if (null === $this->collMeasurementOpinions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMeasurementOpinions) {
                // return empty collection
                $this->initMeasurementOpinions();
            } else {
                $collMeasurementOpinions = MeasurementOpinionQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collMeasurementOpinionsPartial && count($collMeasurementOpinions)) {
                      $this->initMeasurementOpinions(false);

                      foreach ($collMeasurementOpinions as $obj) {
                        if (false == $this->collMeasurementOpinions->contains($obj)) {
                          $this->collMeasurementOpinions->append($obj);
                        }
                      }

                      $this->collMeasurementOpinionsPartial = true;
                    }

                    $collMeasurementOpinions->getInternalIterator()->rewind();

                    return $collMeasurementOpinions;
                }

                if ($partial && $this->collMeasurementOpinions) {
                    foreach ($this->collMeasurementOpinions as $obj) {
                        if ($obj->isNew()) {
                            $collMeasurementOpinions[] = $obj;
                        }
                    }
                }

                $this->collMeasurementOpinions = $collMeasurementOpinions;
                $this->collMeasurementOpinionsPartial = false;
            }
        }

        return $this->collMeasurementOpinions;
    }

    /**
     * Sets a collection of MeasurementOpinion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $measurementOpinions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setMeasurementOpinions(PropelCollection $measurementOpinions, PropelPDO $con = null)
    {
        $measurementOpinionsToDelete = $this->getMeasurementOpinions(new Criteria(), $con)->diff($measurementOpinions);


        $this->measurementOpinionsScheduledForDeletion = $measurementOpinionsToDelete;

        foreach ($measurementOpinionsToDelete as $measurementOpinionRemoved) {
            $measurementOpinionRemoved->setUser(null);
        }

        $this->collMeasurementOpinions = null;
        foreach ($measurementOpinions as $measurementOpinion) {
            $this->addMeasurementOpinion($measurementOpinion);
        }

        $this->collMeasurementOpinions = $measurementOpinions;
        $this->collMeasurementOpinionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related MeasurementOpinion objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related MeasurementOpinion objects.
     * @throws PropelException
     */
    public function countMeasurementOpinions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collMeasurementOpinionsPartial && !$this->isNew();
        if (null === $this->collMeasurementOpinions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMeasurementOpinions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMeasurementOpinions());
            }
            $query = MeasurementOpinionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collMeasurementOpinions);
    }

    /**
     * Method called to associate a BaseMeasurementOpinion object to this object
     * through the BaseMeasurementOpinion foreign key attribute.
     *
     * @param    BaseMeasurementOpinion $l BaseMeasurementOpinion
     * @return User The current object (for fluent API support)
     */
    public function addMeasurementOpinion(BaseMeasurementOpinion $l)
    {
        if ($this->collMeasurementOpinions === null) {
            $this->initMeasurementOpinions();
            $this->collMeasurementOpinionsPartial = true;
        }

        if (!in_array($l, $this->collMeasurementOpinions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMeasurementOpinion($l);

            if ($this->measurementOpinionsScheduledForDeletion and $this->measurementOpinionsScheduledForDeletion->contains($l)) {
                $this->measurementOpinionsScheduledForDeletion->remove($this->measurementOpinionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	MeasurementOpinion $measurementOpinion The measurementOpinion object to add.
     */
    protected function doAddMeasurementOpinion($measurementOpinion)
    {
        $this->collMeasurementOpinions[]= $measurementOpinion;
        $measurementOpinion->setUser($this);
    }

    /**
     * @param	MeasurementOpinion $measurementOpinion The measurementOpinion object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeMeasurementOpinion($measurementOpinion)
    {
        if ($this->getMeasurementOpinions()->contains($measurementOpinion)) {
            $this->collMeasurementOpinions->remove($this->collMeasurementOpinions->search($measurementOpinion));
            if (null === $this->measurementOpinionsScheduledForDeletion) {
                $this->measurementOpinionsScheduledForDeletion = clone $this->collMeasurementOpinions;
                $this->measurementOpinionsScheduledForDeletion->clear();
            }
            $this->measurementOpinionsScheduledForDeletion[]= clone $measurementOpinion;
            $measurementOpinion->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related MeasurementOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|MeasurementOpinion[] List of MeasurementOpinion objects
     */
    public function getMeasurementOpinionsJoinArticle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MeasurementOpinionQuery::create(null, $criteria);
        $query->joinWith('Article', $join_behavior);

        return $this->getMeasurementOpinions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related MeasurementOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|MeasurementOpinion[] List of MeasurementOpinion objects
     */
    public function getMeasurementOpinionsJoinTerm($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MeasurementOpinionQuery::create(null, $criteria);
        $query->joinWith('Term', $join_behavior);

        return $this->getMeasurementOpinions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related MeasurementOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|MeasurementOpinion[] List of MeasurementOpinion objects
     */
    public function getMeasurementOpinionsJoinGroupOpinionRelatedByGroup1Id($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MeasurementOpinionQuery::create(null, $criteria);
        $query->joinWith('GroupOpinionRelatedByGroup1Id', $join_behavior);

        return $this->getMeasurementOpinions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related MeasurementOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|MeasurementOpinion[] List of MeasurementOpinion objects
     */
    public function getMeasurementOpinionsJoinTimepointOpinionRelatedByTimepoint1Id($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MeasurementOpinionQuery::create(null, $criteria);
        $query->joinWith('TimepointOpinionRelatedByTimepoint1Id', $join_behavior);

        return $this->getMeasurementOpinions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related MeasurementOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|MeasurementOpinion[] List of MeasurementOpinion objects
     */
    public function getMeasurementOpinionsJoinGroupOpinionRelatedByGroup2Id($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MeasurementOpinionQuery::create(null, $criteria);
        $query->joinWith('GroupOpinionRelatedByGroup2Id', $join_behavior);

        return $this->getMeasurementOpinions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related MeasurementOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|MeasurementOpinion[] List of MeasurementOpinion objects
     */
    public function getMeasurementOpinionsJoinTimepointOpinionRelatedByTimepoint2Id($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MeasurementOpinionQuery::create(null, $criteria);
        $query->joinWith('TimepointOpinionRelatedByTimepoint2Id', $join_behavior);

        return $this->getMeasurementOpinions($query, $con);
    }

    /**
     * Clears out the collPMQueriesRelatedByScientistId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addPMQueriesRelatedByScientistId()
     */
    public function clearPMQueriesRelatedByScientistId()
    {
        $this->collPMQueriesRelatedByScientistId = null; // important to set this to null since that means it is uninitialized
        $this->collPMQueriesRelatedByScientistIdPartial = null;

        return $this;
    }

    /**
     * reset is the collPMQueriesRelatedByScientistId collection loaded partially
     *
     * @return void
     */
    public function resetPartialPMQueriesRelatedByScientistId($v = true)
    {
        $this->collPMQueriesRelatedByScientistIdPartial = $v;
    }

    /**
     * Initializes the collPMQueriesRelatedByScientistId collection.
     *
     * By default this just sets the collPMQueriesRelatedByScientistId collection to an empty array (like clearcollPMQueriesRelatedByScientistId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPMQueriesRelatedByScientistId($overrideExisting = true)
    {
        if (null !== $this->collPMQueriesRelatedByScientistId && !$overrideExisting) {
            return;
        }
        $this->collPMQueriesRelatedByScientistId = new PropelObjectCollection();
        $this->collPMQueriesRelatedByScientistId->setModel('PMQuery');
    }

    /**
     * Gets an array of PMQuery objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PMQuery[] List of PMQuery objects
     * @throws PropelException
     */
    public function getPMQueriesRelatedByScientistId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPMQueriesRelatedByScientistIdPartial && !$this->isNew();
        if (null === $this->collPMQueriesRelatedByScientistId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPMQueriesRelatedByScientistId) {
                // return empty collection
                $this->initPMQueriesRelatedByScientistId();
            } else {
                $collPMQueriesRelatedByScientistId = PMQueryQuery::create(null, $criteria)
                    ->filterByUserRelatedByScientistId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPMQueriesRelatedByScientistIdPartial && count($collPMQueriesRelatedByScientistId)) {
                      $this->initPMQueriesRelatedByScientistId(false);

                      foreach ($collPMQueriesRelatedByScientistId as $obj) {
                        if (false == $this->collPMQueriesRelatedByScientistId->contains($obj)) {
                          $this->collPMQueriesRelatedByScientistId->append($obj);
                        }
                      }

                      $this->collPMQueriesRelatedByScientistIdPartial = true;
                    }

                    $collPMQueriesRelatedByScientistId->getInternalIterator()->rewind();

                    return $collPMQueriesRelatedByScientistId;
                }

                if ($partial && $this->collPMQueriesRelatedByScientistId) {
                    foreach ($this->collPMQueriesRelatedByScientistId as $obj) {
                        if ($obj->isNew()) {
                            $collPMQueriesRelatedByScientistId[] = $obj;
                        }
                    }
                }

                $this->collPMQueriesRelatedByScientistId = $collPMQueriesRelatedByScientistId;
                $this->collPMQueriesRelatedByScientistIdPartial = false;
            }
        }

        return $this->collPMQueriesRelatedByScientistId;
    }

    /**
     * Sets a collection of PMQueryRelatedByScientistId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pMQueriesRelatedByScientistId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setPMQueriesRelatedByScientistId(PropelCollection $pMQueriesRelatedByScientistId, PropelPDO $con = null)
    {
        $pMQueriesRelatedByScientistIdToDelete = $this->getPMQueriesRelatedByScientistId(new Criteria(), $con)->diff($pMQueriesRelatedByScientistId);


        $this->pMQueriesRelatedByScientistIdScheduledForDeletion = $pMQueriesRelatedByScientistIdToDelete;

        foreach ($pMQueriesRelatedByScientistIdToDelete as $pMQueryRelatedByScientistIdRemoved) {
            $pMQueryRelatedByScientistIdRemoved->setUserRelatedByScientistId(null);
        }

        $this->collPMQueriesRelatedByScientistId = null;
        foreach ($pMQueriesRelatedByScientistId as $pMQueryRelatedByScientistId) {
            $this->addPMQueryRelatedByScientistId($pMQueryRelatedByScientistId);
        }

        $this->collPMQueriesRelatedByScientistId = $pMQueriesRelatedByScientistId;
        $this->collPMQueriesRelatedByScientistIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PMQuery objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PMQuery objects.
     * @throws PropelException
     */
    public function countPMQueriesRelatedByScientistId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPMQueriesRelatedByScientistIdPartial && !$this->isNew();
        if (null === $this->collPMQueriesRelatedByScientistId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPMQueriesRelatedByScientistId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPMQueriesRelatedByScientistId());
            }
            $query = PMQueryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserRelatedByScientistId($this)
                ->count($con);
        }

        return count($this->collPMQueriesRelatedByScientistId);
    }

    /**
     * Method called to associate a PMQuery object to this object
     * through the PMQuery foreign key attribute.
     *
     * @param    PMQuery $l PMQuery
     * @return User The current object (for fluent API support)
     */
    public function addPMQueryRelatedByScientistId(PMQuery $l)
    {
        if ($this->collPMQueriesRelatedByScientistId === null) {
            $this->initPMQueriesRelatedByScientistId();
            $this->collPMQueriesRelatedByScientistIdPartial = true;
        }

        if (!in_array($l, $this->collPMQueriesRelatedByScientistId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPMQueryRelatedByScientistId($l);

            if ($this->pMQueriesRelatedByScientistIdScheduledForDeletion and $this->pMQueriesRelatedByScientistIdScheduledForDeletion->contains($l)) {
                $this->pMQueriesRelatedByScientistIdScheduledForDeletion->remove($this->pMQueriesRelatedByScientistIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PMQueryRelatedByScientistId $pMQueryRelatedByScientistId The pMQueryRelatedByScientistId object to add.
     */
    protected function doAddPMQueryRelatedByScientistId($pMQueryRelatedByScientistId)
    {
        $this->collPMQueriesRelatedByScientistId[]= $pMQueryRelatedByScientistId;
        $pMQueryRelatedByScientistId->setUserRelatedByScientistId($this);
    }

    /**
     * @param	PMQueryRelatedByScientistId $pMQueryRelatedByScientistId The pMQueryRelatedByScientistId object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removePMQueryRelatedByScientistId($pMQueryRelatedByScientistId)
    {
        if ($this->getPMQueriesRelatedByScientistId()->contains($pMQueryRelatedByScientistId)) {
            $this->collPMQueriesRelatedByScientistId->remove($this->collPMQueriesRelatedByScientistId->search($pMQueryRelatedByScientistId));
            if (null === $this->pMQueriesRelatedByScientistIdScheduledForDeletion) {
                $this->pMQueriesRelatedByScientistIdScheduledForDeletion = clone $this->collPMQueriesRelatedByScientistId;
                $this->pMQueriesRelatedByScientistIdScheduledForDeletion->clear();
            }
            $this->pMQueriesRelatedByScientistIdScheduledForDeletion[]= $pMQueryRelatedByScientistId;
            $pMQueryRelatedByScientistId->setUserRelatedByScientistId(null);
        }

        return $this;
    }

    /**
     * Clears out the collPMQueriesRelatedByDataEntry1Id collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addPMQueriesRelatedByDataEntry1Id()
     */
    public function clearPMQueriesRelatedByDataEntry1Id()
    {
        $this->collPMQueriesRelatedByDataEntry1Id = null; // important to set this to null since that means it is uninitialized
        $this->collPMQueriesRelatedByDataEntry1IdPartial = null;

        return $this;
    }

    /**
     * reset is the collPMQueriesRelatedByDataEntry1Id collection loaded partially
     *
     * @return void
     */
    public function resetPartialPMQueriesRelatedByDataEntry1Id($v = true)
    {
        $this->collPMQueriesRelatedByDataEntry1IdPartial = $v;
    }

    /**
     * Initializes the collPMQueriesRelatedByDataEntry1Id collection.
     *
     * By default this just sets the collPMQueriesRelatedByDataEntry1Id collection to an empty array (like clearcollPMQueriesRelatedByDataEntry1Id());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPMQueriesRelatedByDataEntry1Id($overrideExisting = true)
    {
        if (null !== $this->collPMQueriesRelatedByDataEntry1Id && !$overrideExisting) {
            return;
        }
        $this->collPMQueriesRelatedByDataEntry1Id = new PropelObjectCollection();
        $this->collPMQueriesRelatedByDataEntry1Id->setModel('PMQuery');
    }

    /**
     * Gets an array of PMQuery objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PMQuery[] List of PMQuery objects
     * @throws PropelException
     */
    public function getPMQueriesRelatedByDataEntry1Id($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPMQueriesRelatedByDataEntry1IdPartial && !$this->isNew();
        if (null === $this->collPMQueriesRelatedByDataEntry1Id || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPMQueriesRelatedByDataEntry1Id) {
                // return empty collection
                $this->initPMQueriesRelatedByDataEntry1Id();
            } else {
                $collPMQueriesRelatedByDataEntry1Id = PMQueryQuery::create(null, $criteria)
                    ->filterByUserRelatedByDataEntry1Id($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPMQueriesRelatedByDataEntry1IdPartial && count($collPMQueriesRelatedByDataEntry1Id)) {
                      $this->initPMQueriesRelatedByDataEntry1Id(false);

                      foreach ($collPMQueriesRelatedByDataEntry1Id as $obj) {
                        if (false == $this->collPMQueriesRelatedByDataEntry1Id->contains($obj)) {
                          $this->collPMQueriesRelatedByDataEntry1Id->append($obj);
                        }
                      }

                      $this->collPMQueriesRelatedByDataEntry1IdPartial = true;
                    }

                    $collPMQueriesRelatedByDataEntry1Id->getInternalIterator()->rewind();

                    return $collPMQueriesRelatedByDataEntry1Id;
                }

                if ($partial && $this->collPMQueriesRelatedByDataEntry1Id) {
                    foreach ($this->collPMQueriesRelatedByDataEntry1Id as $obj) {
                        if ($obj->isNew()) {
                            $collPMQueriesRelatedByDataEntry1Id[] = $obj;
                        }
                    }
                }

                $this->collPMQueriesRelatedByDataEntry1Id = $collPMQueriesRelatedByDataEntry1Id;
                $this->collPMQueriesRelatedByDataEntry1IdPartial = false;
            }
        }

        return $this->collPMQueriesRelatedByDataEntry1Id;
    }

    /**
     * Sets a collection of PMQueryRelatedByDataEntry1Id objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pMQueriesRelatedByDataEntry1Id A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setPMQueriesRelatedByDataEntry1Id(PropelCollection $pMQueriesRelatedByDataEntry1Id, PropelPDO $con = null)
    {
        $pMQueriesRelatedByDataEntry1IdToDelete = $this->getPMQueriesRelatedByDataEntry1Id(new Criteria(), $con)->diff($pMQueriesRelatedByDataEntry1Id);


        $this->pMQueriesRelatedByDataEntry1IdScheduledForDeletion = $pMQueriesRelatedByDataEntry1IdToDelete;

        foreach ($pMQueriesRelatedByDataEntry1IdToDelete as $pMQueryRelatedByDataEntry1IdRemoved) {
            $pMQueryRelatedByDataEntry1IdRemoved->setUserRelatedByDataEntry1Id(null);
        }

        $this->collPMQueriesRelatedByDataEntry1Id = null;
        foreach ($pMQueriesRelatedByDataEntry1Id as $pMQueryRelatedByDataEntry1Id) {
            $this->addPMQueryRelatedByDataEntry1Id($pMQueryRelatedByDataEntry1Id);
        }

        $this->collPMQueriesRelatedByDataEntry1Id = $pMQueriesRelatedByDataEntry1Id;
        $this->collPMQueriesRelatedByDataEntry1IdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PMQuery objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PMQuery objects.
     * @throws PropelException
     */
    public function countPMQueriesRelatedByDataEntry1Id(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPMQueriesRelatedByDataEntry1IdPartial && !$this->isNew();
        if (null === $this->collPMQueriesRelatedByDataEntry1Id || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPMQueriesRelatedByDataEntry1Id) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPMQueriesRelatedByDataEntry1Id());
            }
            $query = PMQueryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserRelatedByDataEntry1Id($this)
                ->count($con);
        }

        return count($this->collPMQueriesRelatedByDataEntry1Id);
    }

    /**
     * Method called to associate a PMQuery object to this object
     * through the PMQuery foreign key attribute.
     *
     * @param    PMQuery $l PMQuery
     * @return User The current object (for fluent API support)
     */
    public function addPMQueryRelatedByDataEntry1Id(PMQuery $l)
    {
        if ($this->collPMQueriesRelatedByDataEntry1Id === null) {
            $this->initPMQueriesRelatedByDataEntry1Id();
            $this->collPMQueriesRelatedByDataEntry1IdPartial = true;
        }

        if (!in_array($l, $this->collPMQueriesRelatedByDataEntry1Id->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPMQueryRelatedByDataEntry1Id($l);

            if ($this->pMQueriesRelatedByDataEntry1IdScheduledForDeletion and $this->pMQueriesRelatedByDataEntry1IdScheduledForDeletion->contains($l)) {
                $this->pMQueriesRelatedByDataEntry1IdScheduledForDeletion->remove($this->pMQueriesRelatedByDataEntry1IdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PMQueryRelatedByDataEntry1Id $pMQueryRelatedByDataEntry1Id The pMQueryRelatedByDataEntry1Id object to add.
     */
    protected function doAddPMQueryRelatedByDataEntry1Id($pMQueryRelatedByDataEntry1Id)
    {
        $this->collPMQueriesRelatedByDataEntry1Id[]= $pMQueryRelatedByDataEntry1Id;
        $pMQueryRelatedByDataEntry1Id->setUserRelatedByDataEntry1Id($this);
    }

    /**
     * @param	PMQueryRelatedByDataEntry1Id $pMQueryRelatedByDataEntry1Id The pMQueryRelatedByDataEntry1Id object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removePMQueryRelatedByDataEntry1Id($pMQueryRelatedByDataEntry1Id)
    {
        if ($this->getPMQueriesRelatedByDataEntry1Id()->contains($pMQueryRelatedByDataEntry1Id)) {
            $this->collPMQueriesRelatedByDataEntry1Id->remove($this->collPMQueriesRelatedByDataEntry1Id->search($pMQueryRelatedByDataEntry1Id));
            if (null === $this->pMQueriesRelatedByDataEntry1IdScheduledForDeletion) {
                $this->pMQueriesRelatedByDataEntry1IdScheduledForDeletion = clone $this->collPMQueriesRelatedByDataEntry1Id;
                $this->pMQueriesRelatedByDataEntry1IdScheduledForDeletion->clear();
            }
            $this->pMQueriesRelatedByDataEntry1IdScheduledForDeletion[]= $pMQueryRelatedByDataEntry1Id;
            $pMQueryRelatedByDataEntry1Id->setUserRelatedByDataEntry1Id(null);
        }

        return $this;
    }

    /**
     * Clears out the collPMQueriesRelatedByDataEntry2Id collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addPMQueriesRelatedByDataEntry2Id()
     */
    public function clearPMQueriesRelatedByDataEntry2Id()
    {
        $this->collPMQueriesRelatedByDataEntry2Id = null; // important to set this to null since that means it is uninitialized
        $this->collPMQueriesRelatedByDataEntry2IdPartial = null;

        return $this;
    }

    /**
     * reset is the collPMQueriesRelatedByDataEntry2Id collection loaded partially
     *
     * @return void
     */
    public function resetPartialPMQueriesRelatedByDataEntry2Id($v = true)
    {
        $this->collPMQueriesRelatedByDataEntry2IdPartial = $v;
    }

    /**
     * Initializes the collPMQueriesRelatedByDataEntry2Id collection.
     *
     * By default this just sets the collPMQueriesRelatedByDataEntry2Id collection to an empty array (like clearcollPMQueriesRelatedByDataEntry2Id());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPMQueriesRelatedByDataEntry2Id($overrideExisting = true)
    {
        if (null !== $this->collPMQueriesRelatedByDataEntry2Id && !$overrideExisting) {
            return;
        }
        $this->collPMQueriesRelatedByDataEntry2Id = new PropelObjectCollection();
        $this->collPMQueriesRelatedByDataEntry2Id->setModel('PMQuery');
    }

    /**
     * Gets an array of PMQuery objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PMQuery[] List of PMQuery objects
     * @throws PropelException
     */
    public function getPMQueriesRelatedByDataEntry2Id($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPMQueriesRelatedByDataEntry2IdPartial && !$this->isNew();
        if (null === $this->collPMQueriesRelatedByDataEntry2Id || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPMQueriesRelatedByDataEntry2Id) {
                // return empty collection
                $this->initPMQueriesRelatedByDataEntry2Id();
            } else {
                $collPMQueriesRelatedByDataEntry2Id = PMQueryQuery::create(null, $criteria)
                    ->filterByUserRelatedByDataEntry2Id($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPMQueriesRelatedByDataEntry2IdPartial && count($collPMQueriesRelatedByDataEntry2Id)) {
                      $this->initPMQueriesRelatedByDataEntry2Id(false);

                      foreach ($collPMQueriesRelatedByDataEntry2Id as $obj) {
                        if (false == $this->collPMQueriesRelatedByDataEntry2Id->contains($obj)) {
                          $this->collPMQueriesRelatedByDataEntry2Id->append($obj);
                        }
                      }

                      $this->collPMQueriesRelatedByDataEntry2IdPartial = true;
                    }

                    $collPMQueriesRelatedByDataEntry2Id->getInternalIterator()->rewind();

                    return $collPMQueriesRelatedByDataEntry2Id;
                }

                if ($partial && $this->collPMQueriesRelatedByDataEntry2Id) {
                    foreach ($this->collPMQueriesRelatedByDataEntry2Id as $obj) {
                        if ($obj->isNew()) {
                            $collPMQueriesRelatedByDataEntry2Id[] = $obj;
                        }
                    }
                }

                $this->collPMQueriesRelatedByDataEntry2Id = $collPMQueriesRelatedByDataEntry2Id;
                $this->collPMQueriesRelatedByDataEntry2IdPartial = false;
            }
        }

        return $this->collPMQueriesRelatedByDataEntry2Id;
    }

    /**
     * Sets a collection of PMQueryRelatedByDataEntry2Id objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pMQueriesRelatedByDataEntry2Id A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setPMQueriesRelatedByDataEntry2Id(PropelCollection $pMQueriesRelatedByDataEntry2Id, PropelPDO $con = null)
    {
        $pMQueriesRelatedByDataEntry2IdToDelete = $this->getPMQueriesRelatedByDataEntry2Id(new Criteria(), $con)->diff($pMQueriesRelatedByDataEntry2Id);


        $this->pMQueriesRelatedByDataEntry2IdScheduledForDeletion = $pMQueriesRelatedByDataEntry2IdToDelete;

        foreach ($pMQueriesRelatedByDataEntry2IdToDelete as $pMQueryRelatedByDataEntry2IdRemoved) {
            $pMQueryRelatedByDataEntry2IdRemoved->setUserRelatedByDataEntry2Id(null);
        }

        $this->collPMQueriesRelatedByDataEntry2Id = null;
        foreach ($pMQueriesRelatedByDataEntry2Id as $pMQueryRelatedByDataEntry2Id) {
            $this->addPMQueryRelatedByDataEntry2Id($pMQueryRelatedByDataEntry2Id);
        }

        $this->collPMQueriesRelatedByDataEntry2Id = $pMQueriesRelatedByDataEntry2Id;
        $this->collPMQueriesRelatedByDataEntry2IdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PMQuery objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PMQuery objects.
     * @throws PropelException
     */
    public function countPMQueriesRelatedByDataEntry2Id(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPMQueriesRelatedByDataEntry2IdPartial && !$this->isNew();
        if (null === $this->collPMQueriesRelatedByDataEntry2Id || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPMQueriesRelatedByDataEntry2Id) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPMQueriesRelatedByDataEntry2Id());
            }
            $query = PMQueryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserRelatedByDataEntry2Id($this)
                ->count($con);
        }

        return count($this->collPMQueriesRelatedByDataEntry2Id);
    }

    /**
     * Method called to associate a PMQuery object to this object
     * through the PMQuery foreign key attribute.
     *
     * @param    PMQuery $l PMQuery
     * @return User The current object (for fluent API support)
     */
    public function addPMQueryRelatedByDataEntry2Id(PMQuery $l)
    {
        if ($this->collPMQueriesRelatedByDataEntry2Id === null) {
            $this->initPMQueriesRelatedByDataEntry2Id();
            $this->collPMQueriesRelatedByDataEntry2IdPartial = true;
        }

        if (!in_array($l, $this->collPMQueriesRelatedByDataEntry2Id->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPMQueryRelatedByDataEntry2Id($l);

            if ($this->pMQueriesRelatedByDataEntry2IdScheduledForDeletion and $this->pMQueriesRelatedByDataEntry2IdScheduledForDeletion->contains($l)) {
                $this->pMQueriesRelatedByDataEntry2IdScheduledForDeletion->remove($this->pMQueriesRelatedByDataEntry2IdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PMQueryRelatedByDataEntry2Id $pMQueryRelatedByDataEntry2Id The pMQueryRelatedByDataEntry2Id object to add.
     */
    protected function doAddPMQueryRelatedByDataEntry2Id($pMQueryRelatedByDataEntry2Id)
    {
        $this->collPMQueriesRelatedByDataEntry2Id[]= $pMQueryRelatedByDataEntry2Id;
        $pMQueryRelatedByDataEntry2Id->setUserRelatedByDataEntry2Id($this);
    }

    /**
     * @param	PMQueryRelatedByDataEntry2Id $pMQueryRelatedByDataEntry2Id The pMQueryRelatedByDataEntry2Id object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removePMQueryRelatedByDataEntry2Id($pMQueryRelatedByDataEntry2Id)
    {
        if ($this->getPMQueriesRelatedByDataEntry2Id()->contains($pMQueryRelatedByDataEntry2Id)) {
            $this->collPMQueriesRelatedByDataEntry2Id->remove($this->collPMQueriesRelatedByDataEntry2Id->search($pMQueryRelatedByDataEntry2Id));
            if (null === $this->pMQueriesRelatedByDataEntry2IdScheduledForDeletion) {
                $this->pMQueriesRelatedByDataEntry2IdScheduledForDeletion = clone $this->collPMQueriesRelatedByDataEntry2Id;
                $this->pMQueriesRelatedByDataEntry2IdScheduledForDeletion->clear();
            }
            $this->pMQueriesRelatedByDataEntry2IdScheduledForDeletion[]= $pMQueryRelatedByDataEntry2Id;
            $pMQueryRelatedByDataEntry2Id->setUserRelatedByDataEntry2Id(null);
        }

        return $this;
    }

    /**
     * Clears out the collTreatmentOpinions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addTreatmentOpinions()
     */
    public function clearTreatmentOpinions()
    {
        $this->collTreatmentOpinions = null; // important to set this to null since that means it is uninitialized
        $this->collTreatmentOpinionsPartial = null;

        return $this;
    }

    /**
     * reset is the collTreatmentOpinions collection loaded partially
     *
     * @return void
     */
    public function resetPartialTreatmentOpinions($v = true)
    {
        $this->collTreatmentOpinionsPartial = $v;
    }

    /**
     * Initializes the collTreatmentOpinions collection.
     *
     * By default this just sets the collTreatmentOpinions collection to an empty array (like clearcollTreatmentOpinions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTreatmentOpinions($overrideExisting = true)
    {
        if (null !== $this->collTreatmentOpinions && !$overrideExisting) {
            return;
        }
        $this->collTreatmentOpinions = new PropelObjectCollection();
        $this->collTreatmentOpinions->setModel('TreatmentOpinion');
    }

    /**
     * Gets an array of TreatmentOpinion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|TreatmentOpinion[] List of TreatmentOpinion objects
     * @throws PropelException
     */
    public function getTreatmentOpinions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collTreatmentOpinionsPartial && !$this->isNew();
        if (null === $this->collTreatmentOpinions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTreatmentOpinions) {
                // return empty collection
                $this->initTreatmentOpinions();
            } else {
                $collTreatmentOpinions = TreatmentOpinionQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collTreatmentOpinionsPartial && count($collTreatmentOpinions)) {
                      $this->initTreatmentOpinions(false);

                      foreach ($collTreatmentOpinions as $obj) {
                        if (false == $this->collTreatmentOpinions->contains($obj)) {
                          $this->collTreatmentOpinions->append($obj);
                        }
                      }

                      $this->collTreatmentOpinionsPartial = true;
                    }

                    $collTreatmentOpinions->getInternalIterator()->rewind();

                    return $collTreatmentOpinions;
                }

                if ($partial && $this->collTreatmentOpinions) {
                    foreach ($this->collTreatmentOpinions as $obj) {
                        if ($obj->isNew()) {
                            $collTreatmentOpinions[] = $obj;
                        }
                    }
                }

                $this->collTreatmentOpinions = $collTreatmentOpinions;
                $this->collTreatmentOpinionsPartial = false;
            }
        }

        return $this->collTreatmentOpinions;
    }

    /**
     * Sets a collection of TreatmentOpinion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $treatmentOpinions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setTreatmentOpinions(PropelCollection $treatmentOpinions, PropelPDO $con = null)
    {
        $treatmentOpinionsToDelete = $this->getTreatmentOpinions(new Criteria(), $con)->diff($treatmentOpinions);


        $this->treatmentOpinionsScheduledForDeletion = $treatmentOpinionsToDelete;

        foreach ($treatmentOpinionsToDelete as $treatmentOpinionRemoved) {
            $treatmentOpinionRemoved->setUser(null);
        }

        $this->collTreatmentOpinions = null;
        foreach ($treatmentOpinions as $treatmentOpinion) {
            $this->addTreatmentOpinion($treatmentOpinion);
        }

        $this->collTreatmentOpinions = $treatmentOpinions;
        $this->collTreatmentOpinionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related TreatmentOpinion objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related TreatmentOpinion objects.
     * @throws PropelException
     */
    public function countTreatmentOpinions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collTreatmentOpinionsPartial && !$this->isNew();
        if (null === $this->collTreatmentOpinions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTreatmentOpinions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTreatmentOpinions());
            }
            $query = TreatmentOpinionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collTreatmentOpinions);
    }

    /**
     * Method called to associate a TreatmentOpinion object to this object
     * through the TreatmentOpinion foreign key attribute.
     *
     * @param    TreatmentOpinion $l TreatmentOpinion
     * @return User The current object (for fluent API support)
     */
    public function addTreatmentOpinion(TreatmentOpinion $l)
    {
        if ($this->collTreatmentOpinions === null) {
            $this->initTreatmentOpinions();
            $this->collTreatmentOpinionsPartial = true;
        }

        if (!in_array($l, $this->collTreatmentOpinions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddTreatmentOpinion($l);

            if ($this->treatmentOpinionsScheduledForDeletion and $this->treatmentOpinionsScheduledForDeletion->contains($l)) {
                $this->treatmentOpinionsScheduledForDeletion->remove($this->treatmentOpinionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	TreatmentOpinion $treatmentOpinion The treatmentOpinion object to add.
     */
    protected function doAddTreatmentOpinion($treatmentOpinion)
    {
        $this->collTreatmentOpinions[]= $treatmentOpinion;
        $treatmentOpinion->setUser($this);
    }

    /**
     * @param	TreatmentOpinion $treatmentOpinion The treatmentOpinion object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeTreatmentOpinion($treatmentOpinion)
    {
        if ($this->getTreatmentOpinions()->contains($treatmentOpinion)) {
            $this->collTreatmentOpinions->remove($this->collTreatmentOpinions->search($treatmentOpinion));
            if (null === $this->treatmentOpinionsScheduledForDeletion) {
                $this->treatmentOpinionsScheduledForDeletion = clone $this->collTreatmentOpinions;
                $this->treatmentOpinionsScheduledForDeletion->clear();
            }
            $this->treatmentOpinionsScheduledForDeletion[]= clone $treatmentOpinion;
            $treatmentOpinion->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related TreatmentOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|TreatmentOpinion[] List of TreatmentOpinion objects
     */
    public function getTreatmentOpinionsJoinArticle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TreatmentOpinionQuery::create(null, $criteria);
        $query->joinWith('Article', $join_behavior);

        return $this->getTreatmentOpinions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related TreatmentOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|TreatmentOpinion[] List of TreatmentOpinion objects
     */
    public function getTreatmentOpinionsJoinGroupOpinion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TreatmentOpinionQuery::create(null, $criteria);
        $query->joinWith('GroupOpinion', $join_behavior);

        return $this->getTreatmentOpinions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related TreatmentOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|TreatmentOpinion[] List of TreatmentOpinion objects
     */
    public function getTreatmentOpinionsJoinTimepointOpinionRelatedByTimepointOpinion1Id($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TreatmentOpinionQuery::create(null, $criteria);
        $query->joinWith('TimepointOpinionRelatedByTimepointOpinion1Id', $join_behavior);

        return $this->getTreatmentOpinions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related TreatmentOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|TreatmentOpinion[] List of TreatmentOpinion objects
     */
    public function getTreatmentOpinionsJoinTimepointOpinionRelatedByTimepointOpinion2Id($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TreatmentOpinionQuery::create(null, $criteria);
        $query->joinWith('TimepointOpinionRelatedByTimepointOpinion2Id', $join_behavior);

        return $this->getTreatmentOpinions($query, $con);
    }

    /**
     * Clears out the collTimepointOpinions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addTimepointOpinions()
     */
    public function clearTimepointOpinions()
    {
        $this->collTimepointOpinions = null; // important to set this to null since that means it is uninitialized
        $this->collTimepointOpinionsPartial = null;

        return $this;
    }

    /**
     * reset is the collTimepointOpinions collection loaded partially
     *
     * @return void
     */
    public function resetPartialTimepointOpinions($v = true)
    {
        $this->collTimepointOpinionsPartial = $v;
    }

    /**
     * Initializes the collTimepointOpinions collection.
     *
     * By default this just sets the collTimepointOpinions collection to an empty array (like clearcollTimepointOpinions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTimepointOpinions($overrideExisting = true)
    {
        if (null !== $this->collTimepointOpinions && !$overrideExisting) {
            return;
        }
        $this->collTimepointOpinions = new PropelObjectCollection();
        $this->collTimepointOpinions->setModel('TimepointOpinion');
    }

    /**
     * Gets an array of TimepointOpinion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|TimepointOpinion[] List of TimepointOpinion objects
     * @throws PropelException
     */
    public function getTimepointOpinions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collTimepointOpinionsPartial && !$this->isNew();
        if (null === $this->collTimepointOpinions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTimepointOpinions) {
                // return empty collection
                $this->initTimepointOpinions();
            } else {
                $collTimepointOpinions = TimepointOpinionQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collTimepointOpinionsPartial && count($collTimepointOpinions)) {
                      $this->initTimepointOpinions(false);

                      foreach ($collTimepointOpinions as $obj) {
                        if (false == $this->collTimepointOpinions->contains($obj)) {
                          $this->collTimepointOpinions->append($obj);
                        }
                      }

                      $this->collTimepointOpinionsPartial = true;
                    }

                    $collTimepointOpinions->getInternalIterator()->rewind();

                    return $collTimepointOpinions;
                }

                if ($partial && $this->collTimepointOpinions) {
                    foreach ($this->collTimepointOpinions as $obj) {
                        if ($obj->isNew()) {
                            $collTimepointOpinions[] = $obj;
                        }
                    }
                }

                $this->collTimepointOpinions = $collTimepointOpinions;
                $this->collTimepointOpinionsPartial = false;
            }
        }

        return $this->collTimepointOpinions;
    }

    /**
     * Sets a collection of TimepointOpinion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $timepointOpinions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setTimepointOpinions(PropelCollection $timepointOpinions, PropelPDO $con = null)
    {
        $timepointOpinionsToDelete = $this->getTimepointOpinions(new Criteria(), $con)->diff($timepointOpinions);


        $this->timepointOpinionsScheduledForDeletion = $timepointOpinionsToDelete;

        foreach ($timepointOpinionsToDelete as $timepointOpinionRemoved) {
            $timepointOpinionRemoved->setUser(null);
        }

        $this->collTimepointOpinions = null;
        foreach ($timepointOpinions as $timepointOpinion) {
            $this->addTimepointOpinion($timepointOpinion);
        }

        $this->collTimepointOpinions = $timepointOpinions;
        $this->collTimepointOpinionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related TimepointOpinion objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related TimepointOpinion objects.
     * @throws PropelException
     */
    public function countTimepointOpinions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collTimepointOpinionsPartial && !$this->isNew();
        if (null === $this->collTimepointOpinions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTimepointOpinions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTimepointOpinions());
            }
            $query = TimepointOpinionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collTimepointOpinions);
    }

    /**
     * Method called to associate a TimepointOpinion object to this object
     * through the TimepointOpinion foreign key attribute.
     *
     * @param    TimepointOpinion $l TimepointOpinion
     * @return User The current object (for fluent API support)
     */
    public function addTimepointOpinion(TimepointOpinion $l)
    {
        if ($this->collTimepointOpinions === null) {
            $this->initTimepointOpinions();
            $this->collTimepointOpinionsPartial = true;
        }

        if (!in_array($l, $this->collTimepointOpinions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddTimepointOpinion($l);

            if ($this->timepointOpinionsScheduledForDeletion and $this->timepointOpinionsScheduledForDeletion->contains($l)) {
                $this->timepointOpinionsScheduledForDeletion->remove($this->timepointOpinionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	TimepointOpinion $timepointOpinion The timepointOpinion object to add.
     */
    protected function doAddTimepointOpinion($timepointOpinion)
    {
        $this->collTimepointOpinions[]= $timepointOpinion;
        $timepointOpinion->setUser($this);
    }

    /**
     * @param	TimepointOpinion $timepointOpinion The timepointOpinion object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeTimepointOpinion($timepointOpinion)
    {
        if ($this->getTimepointOpinions()->contains($timepointOpinion)) {
            $this->collTimepointOpinions->remove($this->collTimepointOpinions->search($timepointOpinion));
            if (null === $this->timepointOpinionsScheduledForDeletion) {
                $this->timepointOpinionsScheduledForDeletion = clone $this->collTimepointOpinions;
                $this->timepointOpinionsScheduledForDeletion->clear();
            }
            $this->timepointOpinionsScheduledForDeletion[]= $timepointOpinion;
            $timepointOpinion->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related TimepointOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|TimepointOpinion[] List of TimepointOpinion objects
     */
    public function getTimepointOpinionsJoinArticle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TimepointOpinionQuery::create(null, $criteria);
        $query->joinWith('Article', $join_behavior);

        return $this->getTimepointOpinions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related TimepointOpinions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|TimepointOpinion[] List of TimepointOpinion objects
     */
    public function getTimepointOpinionsJoinTimepointUnit($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TimepointOpinionQuery::create(null, $criteria);
        $query->joinWith('TimepointUnit', $join_behavior);

        return $this->getTimepointOpinions($query, $con);
    }

    /**
     * Gets a single UserDetails object, which is related to this object by a one-to-one relationship.
     *
     * @param PropelPDO $con optional connection object
     * @return UserDetails
     * @throws PropelException
     */
    public function getUserDetails(PropelPDO $con = null)
    {

        if ($this->singleUserDetails === null && !$this->isNew()) {
            $this->singleUserDetails = UserDetailsQuery::create()->findPk($this->getPrimaryKey(), $con);
        }

        return $this->singleUserDetails;
    }

    /**
     * Sets a single UserDetails object as related to this object by a one-to-one relationship.
     *
     * @param                  UserDetails $v UserDetails
     * @return User The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserDetails(UserDetails $v = null)
    {
        $this->singleUserDetails = $v;

        // Make sure that that the passed-in UserDetails isn't already associated with this object
        if ($v !== null && $v->getUser(null, false) === null) {
            $v->setUser($this);
        }

        return $this;
    }

    /**
     * Clears out the collSecurityRoles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addSecurityRoles()
     */
    public function clearSecurityRoles()
    {
        $this->collSecurityRoles = null; // important to set this to null since that means it is uninitialized
        $this->collSecurityRolesPartial = null;

        return $this;
    }

    /**
     * Initializes the collSecurityRoles collection.
     *
     * By default this just sets the collSecurityRoles collection to an empty collection (like clearSecurityRoles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initSecurityRoles()
    {
        $this->collSecurityRoles = new PropelObjectCollection();
        $this->collSecurityRoles->setModel('SecurityRole');
    }

    /**
     * Gets a collection of SecurityRole objects related by a many-to-many relationship
     * to the current object by way of the roles_users cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|SecurityRole[] List of SecurityRole objects
     */
    public function getSecurityRoles($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collSecurityRoles || null !== $criteria) {
            if ($this->isNew() && null === $this->collSecurityRoles) {
                // return empty collection
                $this->initSecurityRoles();
            } else {
                $collSecurityRoles = SecurityRoleQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collSecurityRoles;
                }
                $this->collSecurityRoles = $collSecurityRoles;
            }
        }

        return $this->collSecurityRoles;
    }

    /**
     * Sets a collection of SecurityRole objects related by a many-to-many relationship
     * to the current object by way of the roles_users cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $securityRoles A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setSecurityRoles(PropelCollection $securityRoles, PropelPDO $con = null)
    {
        $this->clearSecurityRoles();
        $currentSecurityRoles = $this->getSecurityRoles(null, $con);

        $this->securityRolesScheduledForDeletion = $currentSecurityRoles->diff($securityRoles);

        foreach ($securityRoles as $securityRole) {
            if (!$currentSecurityRoles->contains($securityRole)) {
                $this->doAddSecurityRole($securityRole);
            }
        }

        $this->collSecurityRoles = $securityRoles;

        return $this;
    }

    /**
     * Gets the number of SecurityRole objects related by a many-to-many relationship
     * to the current object by way of the roles_users cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related SecurityRole objects
     */
    public function countSecurityRoles($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collSecurityRoles || null !== $criteria) {
            if ($this->isNew() && null === $this->collSecurityRoles) {
                return 0;
            } else {
                $query = SecurityRoleQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collSecurityRoles);
        }
    }

    /**
     * Associate a SecurityRole object to this object
     * through the roles_users cross reference table.
     *
     * @param  SecurityRole $securityRole The RoleUser object to relate
     * @return User The current object (for fluent API support)
     */
    public function addSecurityRole(SecurityRole $securityRole)
    {
        if ($this->collSecurityRoles === null) {
            $this->initSecurityRoles();
        }

        if (!$this->collSecurityRoles->contains($securityRole)) { // only add it if the **same** object is not already associated
            $this->doAddSecurityRole($securityRole);
            $this->collSecurityRoles[] = $securityRole;

            if ($this->securityRolesScheduledForDeletion and $this->securityRolesScheduledForDeletion->contains($securityRole)) {
                $this->securityRolesScheduledForDeletion->remove($this->securityRolesScheduledForDeletion->search($securityRole));
            }
        }

        return $this;
    }

    /**
     * @param	SecurityRole $securityRole The securityRole object to add.
     */
    protected function doAddSecurityRole(SecurityRole $securityRole)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$securityRole->getUsers()->contains($this)) { $roleUser = new RoleUser();
            $roleUser->setSecurityRole($securityRole);
            $this->addRoleUser($roleUser);

            $foreignCollection = $securityRole->getUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a SecurityRole object to this object
     * through the roles_users cross reference table.
     *
     * @param SecurityRole $securityRole The RoleUser object to relate
     * @return User The current object (for fluent API support)
     */
    public function removeSecurityRole(SecurityRole $securityRole)
    {
        if ($this->getSecurityRoles()->contains($securityRole)) {
            $this->collSecurityRoles->remove($this->collSecurityRoles->search($securityRole));
            if (null === $this->securityRolesScheduledForDeletion) {
                $this->securityRolesScheduledForDeletion = clone $this->collSecurityRoles;
                $this->securityRolesScheduledForDeletion->clear();
            }
            $this->securityRolesScheduledForDeletion[]= $securityRole;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->email = null;
        $this->password = null;
        $this->salt = null;
        $this->reset_token = null;
        $this->reset_token_expire = null;
        $this->created_date = null;
        $this->last_login_date = null;
        $this->is_active = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collRoleUsers) {
                foreach ($this->collRoleUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collArticleOpinions) {
                foreach ($this->collArticleOpinions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAuditLogs) {
                foreach ($this->collAuditLogs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collBaselineStateTableMetadatas) {
                foreach ($this->collBaselineStateTableMetadatas as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFullReviewOpinions) {
                foreach ($this->collFullReviewOpinions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFullReviewOpinionLogsRelatedByUserIdFrom) {
                foreach ($this->collFullReviewOpinionLogsRelatedByUserIdFrom as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFullReviewOpinionLogsRelatedByUserIdTo) {
                foreach ($this->collFullReviewOpinionLogsRelatedByUserIdTo as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFullReviewOpinionApprovals) {
                foreach ($this->collFullReviewOpinionApprovals as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGroupOpinions) {
                foreach ($this->collGroupOpinions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collInitialReviewOpinions) {
                foreach ($this->collInitialReviewOpinions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collInitialReviewOpinionLogsRelatedByUserIdFrom) {
                foreach ($this->collInitialReviewOpinionLogsRelatedByUserIdFrom as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collInitialReviewOpinionLogsRelatedByUserIdTo) {
                foreach ($this->collInitialReviewOpinionLogsRelatedByUserIdTo as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMeasurementOpinions) {
                foreach ($this->collMeasurementOpinions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMQueriesRelatedByScientistId) {
                foreach ($this->collPMQueriesRelatedByScientistId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMQueriesRelatedByDataEntry1Id) {
                foreach ($this->collPMQueriesRelatedByDataEntry1Id as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMQueriesRelatedByDataEntry2Id) {
                foreach ($this->collPMQueriesRelatedByDataEntry2Id as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTreatmentOpinions) {
                foreach ($this->collTreatmentOpinions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTimepointOpinions) {
                foreach ($this->collTimepointOpinions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->singleUserDetails) {
                $this->singleUserDetails->clearAllReferences($deep);
            }
            if ($this->collSecurityRoles) {
                foreach ($this->collSecurityRoles as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collRoleUsers instanceof PropelCollection) {
            $this->collRoleUsers->clearIterator();
        }
        $this->collRoleUsers = null;
        if ($this->collArticleOpinions instanceof PropelCollection) {
            $this->collArticleOpinions->clearIterator();
        }
        $this->collArticleOpinions = null;
        if ($this->collAuditLogs instanceof PropelCollection) {
            $this->collAuditLogs->clearIterator();
        }
        $this->collAuditLogs = null;
        if ($this->collBaselineStateTableMetadatas instanceof PropelCollection) {
            $this->collBaselineStateTableMetadatas->clearIterator();
        }
        $this->collBaselineStateTableMetadatas = null;
        if ($this->collFullReviewOpinions instanceof PropelCollection) {
            $this->collFullReviewOpinions->clearIterator();
        }
        $this->collFullReviewOpinions = null;
        if ($this->collFullReviewOpinionLogsRelatedByUserIdFrom instanceof PropelCollection) {
            $this->collFullReviewOpinionLogsRelatedByUserIdFrom->clearIterator();
        }
        $this->collFullReviewOpinionLogsRelatedByUserIdFrom = null;
        if ($this->collFullReviewOpinionLogsRelatedByUserIdTo instanceof PropelCollection) {
            $this->collFullReviewOpinionLogsRelatedByUserIdTo->clearIterator();
        }
        $this->collFullReviewOpinionLogsRelatedByUserIdTo = null;
        if ($this->collFullReviewOpinionApprovals instanceof PropelCollection) {
            $this->collFullReviewOpinionApprovals->clearIterator();
        }
        $this->collFullReviewOpinionApprovals = null;
        if ($this->collGroupOpinions instanceof PropelCollection) {
            $this->collGroupOpinions->clearIterator();
        }
        $this->collGroupOpinions = null;
        if ($this->collInitialReviewOpinions instanceof PropelCollection) {
            $this->collInitialReviewOpinions->clearIterator();
        }
        $this->collInitialReviewOpinions = null;
        if ($this->collInitialReviewOpinionLogsRelatedByUserIdFrom instanceof PropelCollection) {
            $this->collInitialReviewOpinionLogsRelatedByUserIdFrom->clearIterator();
        }
        $this->collInitialReviewOpinionLogsRelatedByUserIdFrom = null;
        if ($this->collInitialReviewOpinionLogsRelatedByUserIdTo instanceof PropelCollection) {
            $this->collInitialReviewOpinionLogsRelatedByUserIdTo->clearIterator();
        }
        $this->collInitialReviewOpinionLogsRelatedByUserIdTo = null;
        if ($this->collMeasurementOpinions instanceof PropelCollection) {
            $this->collMeasurementOpinions->clearIterator();
        }
        $this->collMeasurementOpinions = null;
        if ($this->collPMQueriesRelatedByScientistId instanceof PropelCollection) {
            $this->collPMQueriesRelatedByScientistId->clearIterator();
        }
        $this->collPMQueriesRelatedByScientistId = null;
        if ($this->collPMQueriesRelatedByDataEntry1Id instanceof PropelCollection) {
            $this->collPMQueriesRelatedByDataEntry1Id->clearIterator();
        }
        $this->collPMQueriesRelatedByDataEntry1Id = null;
        if ($this->collPMQueriesRelatedByDataEntry2Id instanceof PropelCollection) {
            $this->collPMQueriesRelatedByDataEntry2Id->clearIterator();
        }
        $this->collPMQueriesRelatedByDataEntry2Id = null;
        if ($this->collTreatmentOpinions instanceof PropelCollection) {
            $this->collTreatmentOpinions->clearIterator();
        }
        $this->collTreatmentOpinions = null;
        if ($this->collTimepointOpinions instanceof PropelCollection) {
            $this->collTimepointOpinions->clearIterator();
        }
        $this->collTimepointOpinions = null;
        if ($this->singleUserDetails instanceof PropelCollection) {
            $this->singleUserDetails->clearIterator();
        }
        $this->singleUserDetails = null;
        if ($this->collSecurityRoles instanceof PropelCollection) {
            $this->collSecurityRoles->clearIterator();
        }
        $this->collSecurityRoles = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

}
