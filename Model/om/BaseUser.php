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
