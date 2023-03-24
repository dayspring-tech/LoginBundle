<?php

namespace Dayspring\LoginBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Dayspring\LoginBundle\Model\RoleUser;
use Dayspring\LoginBundle\Model\SecurityRole;
use Dayspring\LoginBundle\Model\User;
use Dayspring\LoginBundle\Model\UserPeer;
use Dayspring\LoginBundle\Model\UserQuery;
use GOEDCSD\CommonBundle\Model\ArticleOpinion;
use GOEDCSD\CommonBundle\Model\AuditLog;
use GOEDCSD\CommonBundle\Model\BaselineStateTableMetadata;
use GOEDCSD\CommonBundle\Model\FullReviewOpinion;
use GOEDCSD\CommonBundle\Model\FullReviewOpinionApproval;
use GOEDCSD\CommonBundle\Model\FullReviewOpinionLog;
use GOEDCSD\CommonBundle\Model\GroupOpinion;
use GOEDCSD\CommonBundle\Model\InitialReviewOpinion;
use GOEDCSD\CommonBundle\Model\InitialReviewOpinionLog;
use GOEDCSD\CommonBundle\Model\MeasurementOpinion;
use GOEDCSD\CommonBundle\Model\PMQuery;
use GOEDCSD\CommonBundle\Model\TimepointOpinion;
use GOEDCSD\CommonBundle\Model\TreatmentOpinion;
use GOEDCSD\CommonBundle\Model\UserDetails;

/**
 * @method UserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method UserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method UserQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method UserQuery orderBySalt($order = Criteria::ASC) Order by the salt column
 * @method UserQuery orderByResetToken($order = Criteria::ASC) Order by the reset_token column
 * @method UserQuery orderByResetTokenExpire($order = Criteria::ASC) Order by the reset_token_expire column
 * @method UserQuery orderByCreatedDate($order = Criteria::ASC) Order by the created_date column
 * @method UserQuery orderByLastLoginDate($order = Criteria::ASC) Order by the last_login_date column
 * @method UserQuery orderByIsActive($order = Criteria::ASC) Order by the is_active column
 *
 * @method UserQuery groupById() Group by the id column
 * @method UserQuery groupByEmail() Group by the email column
 * @method UserQuery groupByPassword() Group by the password column
 * @method UserQuery groupBySalt() Group by the salt column
 * @method UserQuery groupByResetToken() Group by the reset_token column
 * @method UserQuery groupByResetTokenExpire() Group by the reset_token_expire column
 * @method UserQuery groupByCreatedDate() Group by the created_date column
 * @method UserQuery groupByLastLoginDate() Group by the last_login_date column
 * @method UserQuery groupByIsActive() Group by the is_active column
 *
 * @method UserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UserQuery leftJoinRoleUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the RoleUser relation
 * @method UserQuery rightJoinRoleUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the RoleUser relation
 * @method UserQuery innerJoinRoleUser($relationAlias = null) Adds a INNER JOIN clause to the query using the RoleUser relation
 *
 * @method UserQuery leftJoinArticleOpinion($relationAlias = null) Adds a LEFT JOIN clause to the query using the ArticleOpinion relation
 * @method UserQuery rightJoinArticleOpinion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ArticleOpinion relation
 * @method UserQuery innerJoinArticleOpinion($relationAlias = null) Adds a INNER JOIN clause to the query using the ArticleOpinion relation
 *
 * @method UserQuery leftJoinAuditLog($relationAlias = null) Adds a LEFT JOIN clause to the query using the AuditLog relation
 * @method UserQuery rightJoinAuditLog($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AuditLog relation
 * @method UserQuery innerJoinAuditLog($relationAlias = null) Adds a INNER JOIN clause to the query using the AuditLog relation
 *
 * @method UserQuery leftJoinBaselineStateTableMetadata($relationAlias = null) Adds a LEFT JOIN clause to the query using the BaselineStateTableMetadata relation
 * @method UserQuery rightJoinBaselineStateTableMetadata($relationAlias = null) Adds a RIGHT JOIN clause to the query using the BaselineStateTableMetadata relation
 * @method UserQuery innerJoinBaselineStateTableMetadata($relationAlias = null) Adds a INNER JOIN clause to the query using the BaselineStateTableMetadata relation
 *
 * @method UserQuery leftJoinFullReviewOpinion($relationAlias = null) Adds a LEFT JOIN clause to the query using the FullReviewOpinion relation
 * @method UserQuery rightJoinFullReviewOpinion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FullReviewOpinion relation
 * @method UserQuery innerJoinFullReviewOpinion($relationAlias = null) Adds a INNER JOIN clause to the query using the FullReviewOpinion relation
 *
 * @method UserQuery leftJoinFullReviewOpinionLogRelatedByUserIdFrom($relationAlias = null) Adds a LEFT JOIN clause to the query using the FullReviewOpinionLogRelatedByUserIdFrom relation
 * @method UserQuery rightJoinFullReviewOpinionLogRelatedByUserIdFrom($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FullReviewOpinionLogRelatedByUserIdFrom relation
 * @method UserQuery innerJoinFullReviewOpinionLogRelatedByUserIdFrom($relationAlias = null) Adds a INNER JOIN clause to the query using the FullReviewOpinionLogRelatedByUserIdFrom relation
 *
 * @method UserQuery leftJoinFullReviewOpinionLogRelatedByUserIdTo($relationAlias = null) Adds a LEFT JOIN clause to the query using the FullReviewOpinionLogRelatedByUserIdTo relation
 * @method UserQuery rightJoinFullReviewOpinionLogRelatedByUserIdTo($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FullReviewOpinionLogRelatedByUserIdTo relation
 * @method UserQuery innerJoinFullReviewOpinionLogRelatedByUserIdTo($relationAlias = null) Adds a INNER JOIN clause to the query using the FullReviewOpinionLogRelatedByUserIdTo relation
 *
 * @method UserQuery leftJoinFullReviewOpinionApproval($relationAlias = null) Adds a LEFT JOIN clause to the query using the FullReviewOpinionApproval relation
 * @method UserQuery rightJoinFullReviewOpinionApproval($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FullReviewOpinionApproval relation
 * @method UserQuery innerJoinFullReviewOpinionApproval($relationAlias = null) Adds a INNER JOIN clause to the query using the FullReviewOpinionApproval relation
 *
 * @method UserQuery leftJoinGroupOpinion($relationAlias = null) Adds a LEFT JOIN clause to the query using the GroupOpinion relation
 * @method UserQuery rightJoinGroupOpinion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GroupOpinion relation
 * @method UserQuery innerJoinGroupOpinion($relationAlias = null) Adds a INNER JOIN clause to the query using the GroupOpinion relation
 *
 * @method UserQuery leftJoinInitialReviewOpinion($relationAlias = null) Adds a LEFT JOIN clause to the query using the InitialReviewOpinion relation
 * @method UserQuery rightJoinInitialReviewOpinion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InitialReviewOpinion relation
 * @method UserQuery innerJoinInitialReviewOpinion($relationAlias = null) Adds a INNER JOIN clause to the query using the InitialReviewOpinion relation
 *
 * @method UserQuery leftJoinInitialReviewOpinionLogRelatedByUserIdFrom($relationAlias = null) Adds a LEFT JOIN clause to the query using the InitialReviewOpinionLogRelatedByUserIdFrom relation
 * @method UserQuery rightJoinInitialReviewOpinionLogRelatedByUserIdFrom($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InitialReviewOpinionLogRelatedByUserIdFrom relation
 * @method UserQuery innerJoinInitialReviewOpinionLogRelatedByUserIdFrom($relationAlias = null) Adds a INNER JOIN clause to the query using the InitialReviewOpinionLogRelatedByUserIdFrom relation
 *
 * @method UserQuery leftJoinInitialReviewOpinionLogRelatedByUserIdTo($relationAlias = null) Adds a LEFT JOIN clause to the query using the InitialReviewOpinionLogRelatedByUserIdTo relation
 * @method UserQuery rightJoinInitialReviewOpinionLogRelatedByUserIdTo($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InitialReviewOpinionLogRelatedByUserIdTo relation
 * @method UserQuery innerJoinInitialReviewOpinionLogRelatedByUserIdTo($relationAlias = null) Adds a INNER JOIN clause to the query using the InitialReviewOpinionLogRelatedByUserIdTo relation
 *
 * @method UserQuery leftJoinMeasurementOpinion($relationAlias = null) Adds a LEFT JOIN clause to the query using the MeasurementOpinion relation
 * @method UserQuery rightJoinMeasurementOpinion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MeasurementOpinion relation
 * @method UserQuery innerJoinMeasurementOpinion($relationAlias = null) Adds a INNER JOIN clause to the query using the MeasurementOpinion relation
 *
 * @method UserQuery leftJoinPMQueryRelatedByScientistId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PMQueryRelatedByScientistId relation
 * @method UserQuery rightJoinPMQueryRelatedByScientistId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PMQueryRelatedByScientistId relation
 * @method UserQuery innerJoinPMQueryRelatedByScientistId($relationAlias = null) Adds a INNER JOIN clause to the query using the PMQueryRelatedByScientistId relation
 *
 * @method UserQuery leftJoinPMQueryRelatedByDataEntry1Id($relationAlias = null) Adds a LEFT JOIN clause to the query using the PMQueryRelatedByDataEntry1Id relation
 * @method UserQuery rightJoinPMQueryRelatedByDataEntry1Id($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PMQueryRelatedByDataEntry1Id relation
 * @method UserQuery innerJoinPMQueryRelatedByDataEntry1Id($relationAlias = null) Adds a INNER JOIN clause to the query using the PMQueryRelatedByDataEntry1Id relation
 *
 * @method UserQuery leftJoinPMQueryRelatedByDataEntry2Id($relationAlias = null) Adds a LEFT JOIN clause to the query using the PMQueryRelatedByDataEntry2Id relation
 * @method UserQuery rightJoinPMQueryRelatedByDataEntry2Id($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PMQueryRelatedByDataEntry2Id relation
 * @method UserQuery innerJoinPMQueryRelatedByDataEntry2Id($relationAlias = null) Adds a INNER JOIN clause to the query using the PMQueryRelatedByDataEntry2Id relation
 *
 * @method UserQuery leftJoinTreatmentOpinion($relationAlias = null) Adds a LEFT JOIN clause to the query using the TreatmentOpinion relation
 * @method UserQuery rightJoinTreatmentOpinion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TreatmentOpinion relation
 * @method UserQuery innerJoinTreatmentOpinion($relationAlias = null) Adds a INNER JOIN clause to the query using the TreatmentOpinion relation
 *
 * @method UserQuery leftJoinTimepointOpinion($relationAlias = null) Adds a LEFT JOIN clause to the query using the TimepointOpinion relation
 * @method UserQuery rightJoinTimepointOpinion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TimepointOpinion relation
 * @method UserQuery innerJoinTimepointOpinion($relationAlias = null) Adds a INNER JOIN clause to the query using the TimepointOpinion relation
 *
 * @method UserQuery leftJoinUserDetails($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserDetails relation
 * @method UserQuery rightJoinUserDetails($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserDetails relation
 * @method UserQuery innerJoinUserDetails($relationAlias = null) Adds a INNER JOIN clause to the query using the UserDetails relation
 *
 * @method User findOne(PropelPDO $con = null) Return the first User matching the query
 * @method User findOneOrCreate(PropelPDO $con = null) Return the first User matching the query, or a new User object populated from the query conditions when no match is found
 *
 * @method User findOneByEmail(string $email) Return the first User filtered by the email column
 * @method User findOneByPassword(string $password) Return the first User filtered by the password column
 * @method User findOneBySalt(string $salt) Return the first User filtered by the salt column
 * @method User findOneByResetToken(string $reset_token) Return the first User filtered by the reset_token column
 * @method User findOneByResetTokenExpire(string $reset_token_expire) Return the first User filtered by the reset_token_expire column
 * @method User findOneByCreatedDate(string $created_date) Return the first User filtered by the created_date column
 * @method User findOneByLastLoginDate(string $last_login_date) Return the first User filtered by the last_login_date column
 * @method User findOneByIsActive(boolean $is_active) Return the first User filtered by the is_active column
 *
 * @method array findById(int $id) Return User objects filtered by the id column
 * @method array findByEmail(string $email) Return User objects filtered by the email column
 * @method array findByPassword(string $password) Return User objects filtered by the password column
 * @method array findBySalt(string $salt) Return User objects filtered by the salt column
 * @method array findByResetToken(string $reset_token) Return User objects filtered by the reset_token column
 * @method array findByResetTokenExpire(string $reset_token_expire) Return User objects filtered by the reset_token_expire column
 * @method array findByCreatedDate(string $created_date) Return User objects filtered by the created_date column
 * @method array findByLastLoginDate(string $last_login_date) Return User objects filtered by the last_login_date column
 * @method array findByIsActive(boolean $is_active) Return User objects filtered by the is_active column
 */
abstract class BaseUserQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseUserQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'Dayspring\\LoginBundle\\Model\\User';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new UserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   UserQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UserQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UserQuery) {
            return $criteria;
        }
        $query = new UserQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   User|User[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 User A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 User A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `email`, `password`, `salt`, `reset_token`, `reset_token_expire`, `created_date`, `last_login_date`, `is_active` FROM `users` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new User();
            $obj->hydrate($row);
            UserPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return User|User[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|User[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%'); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $password)) {
                $password = str_replace('*', '%', $password);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the salt column
     *
     * Example usage:
     * <code>
     * $query->filterBySalt('fooValue');   // WHERE salt = 'fooValue'
     * $query->filterBySalt('%fooValue%'); // WHERE salt LIKE '%fooValue%'
     * </code>
     *
     * @param     string $salt The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterBySalt($salt = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($salt)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $salt)) {
                $salt = str_replace('*', '%', $salt);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::SALT, $salt, $comparison);
    }

    /**
     * Filter the query on the reset_token column
     *
     * Example usage:
     * <code>
     * $query->filterByResetToken('fooValue');   // WHERE reset_token = 'fooValue'
     * $query->filterByResetToken('%fooValue%'); // WHERE reset_token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $resetToken The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByResetToken($resetToken = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($resetToken)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $resetToken)) {
                $resetToken = str_replace('*', '%', $resetToken);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::RESET_TOKEN, $resetToken, $comparison);
    }

    /**
     * Filter the query on the reset_token_expire column
     *
     * Example usage:
     * <code>
     * $query->filterByResetTokenExpire('2011-03-14'); // WHERE reset_token_expire = '2011-03-14'
     * $query->filterByResetTokenExpire('now'); // WHERE reset_token_expire = '2011-03-14'
     * $query->filterByResetTokenExpire(array('max' => 'yesterday')); // WHERE reset_token_expire < '2011-03-13'
     * </code>
     *
     * @param     mixed $resetTokenExpire The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByResetTokenExpire($resetTokenExpire = null, $comparison = null)
    {
        if (is_array($resetTokenExpire)) {
            $useMinMax = false;
            if (isset($resetTokenExpire['min'])) {
                $this->addUsingAlias(UserPeer::RESET_TOKEN_EXPIRE, $resetTokenExpire['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($resetTokenExpire['max'])) {
                $this->addUsingAlias(UserPeer::RESET_TOKEN_EXPIRE, $resetTokenExpire['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::RESET_TOKEN_EXPIRE, $resetTokenExpire, $comparison);
    }

    /**
     * Filter the query on the created_date column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedDate('2011-03-14'); // WHERE created_date = '2011-03-14'
     * $query->filterByCreatedDate('now'); // WHERE created_date = '2011-03-14'
     * $query->filterByCreatedDate(array('max' => 'yesterday')); // WHERE created_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $createdDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByCreatedDate($createdDate = null, $comparison = null)
    {
        if (is_array($createdDate)) {
            $useMinMax = false;
            if (isset($createdDate['min'])) {
                $this->addUsingAlias(UserPeer::CREATED_DATE, $createdDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdDate['max'])) {
                $this->addUsingAlias(UserPeer::CREATED_DATE, $createdDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::CREATED_DATE, $createdDate, $comparison);
    }

    /**
     * Filter the query on the last_login_date column
     *
     * Example usage:
     * <code>
     * $query->filterByLastLoginDate('2011-03-14'); // WHERE last_login_date = '2011-03-14'
     * $query->filterByLastLoginDate('now'); // WHERE last_login_date = '2011-03-14'
     * $query->filterByLastLoginDate(array('max' => 'yesterday')); // WHERE last_login_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $lastLoginDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByLastLoginDate($lastLoginDate = null, $comparison = null)
    {
        if (is_array($lastLoginDate)) {
            $useMinMax = false;
            if (isset($lastLoginDate['min'])) {
                $this->addUsingAlias(UserPeer::LAST_LOGIN_DATE, $lastLoginDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastLoginDate['max'])) {
                $this->addUsingAlias(UserPeer::LAST_LOGIN_DATE, $lastLoginDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::LAST_LOGIN_DATE, $lastLoginDate, $comparison);
    }

    /**
     * Filter the query on the is_active column
     *
     * Example usage:
     * <code>
     * $query->filterByIsActive(true); // WHERE is_active = true
     * $query->filterByIsActive('yes'); // WHERE is_active = true
     * </code>
     *
     * @param     boolean|string $isActive The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByIsActive($isActive = null, $comparison = null)
    {
        if (is_string($isActive)) {
            $isActive = in_array(strtolower($isActive), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserPeer::IS_ACTIVE, $isActive, $comparison);
    }

    /**
     * Filter the query by a related RoleUser object
     *
     * @param   RoleUser|PropelObjectCollection $roleUser  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByRoleUser($roleUser, $comparison = null)
    {
        if ($roleUser instanceof RoleUser) {
            return $this
                ->addUsingAlias(UserPeer::ID, $roleUser->getUserId(), $comparison);
        } elseif ($roleUser instanceof PropelObjectCollection) {
            return $this
                ->useRoleUserQuery()
                ->filterByPrimaryKeys($roleUser->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByRoleUser() only accepts arguments of type RoleUser or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the RoleUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinRoleUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('RoleUser');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'RoleUser');
        }

        return $this;
    }

    /**
     * Use the RoleUser relation RoleUser object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Dayspring\LoginBundle\Model\RoleUserQuery A secondary query class using the current class as primary query
     */
    public function useRoleUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinRoleUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'RoleUser', '\Dayspring\LoginBundle\Model\RoleUserQuery');
    }

    /**
     * Filter the query by a related ArticleOpinion object
     *
     * @param   ArticleOpinion|PropelObjectCollection $articleOpinion  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByArticleOpinion($articleOpinion, $comparison = null)
    {
        if ($articleOpinion instanceof ArticleOpinion) {
            return $this
                ->addUsingAlias(UserPeer::ID, $articleOpinion->getUserId(), $comparison);
        } elseif ($articleOpinion instanceof PropelObjectCollection) {
            return $this
                ->useArticleOpinionQuery()
                ->filterByPrimaryKeys($articleOpinion->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByArticleOpinion() only accepts arguments of type ArticleOpinion or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ArticleOpinion relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinArticleOpinion($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ArticleOpinion');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ArticleOpinion');
        }

        return $this;
    }

    /**
     * Use the ArticleOpinion relation ArticleOpinion object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\ArticleOpinionQuery A secondary query class using the current class as primary query
     */
    public function useArticleOpinionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinArticleOpinion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ArticleOpinion', '\GOEDCSD\CommonBundle\Model\ArticleOpinionQuery');
    }

    /**
     * Filter the query by a related AuditLog object
     *
     * @param   AuditLog|PropelObjectCollection $auditLog  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByAuditLog($auditLog, $comparison = null)
    {
        if ($auditLog instanceof AuditLog) {
            return $this
                ->addUsingAlias(UserPeer::ID, $auditLog->getUserId(), $comparison);
        } elseif ($auditLog instanceof PropelObjectCollection) {
            return $this
                ->useAuditLogQuery()
                ->filterByPrimaryKeys($auditLog->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAuditLog() only accepts arguments of type AuditLog or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AuditLog relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinAuditLog($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AuditLog');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'AuditLog');
        }

        return $this;
    }

    /**
     * Use the AuditLog relation AuditLog object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\AuditLogQuery A secondary query class using the current class as primary query
     */
    public function useAuditLogQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAuditLog($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AuditLog', '\GOEDCSD\CommonBundle\Model\AuditLogQuery');
    }

    /**
     * Filter the query by a related BaselineStateTableMetadata object
     *
     * @param   BaselineStateTableMetadata|PropelObjectCollection $baselineStateTableMetadata  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByBaselineStateTableMetadata($baselineStateTableMetadata, $comparison = null)
    {
        if ($baselineStateTableMetadata instanceof BaselineStateTableMetadata) {
            return $this
                ->addUsingAlias(UserPeer::ID, $baselineStateTableMetadata->getUserId(), $comparison);
        } elseif ($baselineStateTableMetadata instanceof PropelObjectCollection) {
            return $this
                ->useBaselineStateTableMetadataQuery()
                ->filterByPrimaryKeys($baselineStateTableMetadata->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByBaselineStateTableMetadata() only accepts arguments of type BaselineStateTableMetadata or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the BaselineStateTableMetadata relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinBaselineStateTableMetadata($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('BaselineStateTableMetadata');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'BaselineStateTableMetadata');
        }

        return $this;
    }

    /**
     * Use the BaselineStateTableMetadata relation BaselineStateTableMetadata object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\BaselineStateTableMetadataQuery A secondary query class using the current class as primary query
     */
    public function useBaselineStateTableMetadataQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinBaselineStateTableMetadata($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'BaselineStateTableMetadata', '\GOEDCSD\CommonBundle\Model\BaselineStateTableMetadataQuery');
    }

    /**
     * Filter the query by a related FullReviewOpinion object
     *
     * @param   FullReviewOpinion|PropelObjectCollection $fullReviewOpinion  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFullReviewOpinion($fullReviewOpinion, $comparison = null)
    {
        if ($fullReviewOpinion instanceof FullReviewOpinion) {
            return $this
                ->addUsingAlias(UserPeer::ID, $fullReviewOpinion->getUserId(), $comparison);
        } elseif ($fullReviewOpinion instanceof PropelObjectCollection) {
            return $this
                ->useFullReviewOpinionQuery()
                ->filterByPrimaryKeys($fullReviewOpinion->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFullReviewOpinion() only accepts arguments of type FullReviewOpinion or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FullReviewOpinion relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinFullReviewOpinion($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FullReviewOpinion');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FullReviewOpinion');
        }

        return $this;
    }

    /**
     * Use the FullReviewOpinion relation FullReviewOpinion object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\FullReviewOpinionQuery A secondary query class using the current class as primary query
     */
    public function useFullReviewOpinionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFullReviewOpinion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FullReviewOpinion', '\GOEDCSD\CommonBundle\Model\FullReviewOpinionQuery');
    }

    /**
     * Filter the query by a related FullReviewOpinionLog object
     *
     * @param   FullReviewOpinionLog|PropelObjectCollection $fullReviewOpinionLog  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFullReviewOpinionLogRelatedByUserIdFrom($fullReviewOpinionLog, $comparison = null)
    {
        if ($fullReviewOpinionLog instanceof FullReviewOpinionLog) {
            return $this
                ->addUsingAlias(UserPeer::ID, $fullReviewOpinionLog->getUserIdFrom(), $comparison);
        } elseif ($fullReviewOpinionLog instanceof PropelObjectCollection) {
            return $this
                ->useFullReviewOpinionLogRelatedByUserIdFromQuery()
                ->filterByPrimaryKeys($fullReviewOpinionLog->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFullReviewOpinionLogRelatedByUserIdFrom() only accepts arguments of type FullReviewOpinionLog or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FullReviewOpinionLogRelatedByUserIdFrom relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinFullReviewOpinionLogRelatedByUserIdFrom($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FullReviewOpinionLogRelatedByUserIdFrom');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FullReviewOpinionLogRelatedByUserIdFrom');
        }

        return $this;
    }

    /**
     * Use the FullReviewOpinionLogRelatedByUserIdFrom relation FullReviewOpinionLog object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\FullReviewOpinionLogQuery A secondary query class using the current class as primary query
     */
    public function useFullReviewOpinionLogRelatedByUserIdFromQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFullReviewOpinionLogRelatedByUserIdFrom($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FullReviewOpinionLogRelatedByUserIdFrom', '\GOEDCSD\CommonBundle\Model\FullReviewOpinionLogQuery');
    }

    /**
     * Filter the query by a related FullReviewOpinionLog object
     *
     * @param   FullReviewOpinionLog|PropelObjectCollection $fullReviewOpinionLog  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFullReviewOpinionLogRelatedByUserIdTo($fullReviewOpinionLog, $comparison = null)
    {
        if ($fullReviewOpinionLog instanceof FullReviewOpinionLog) {
            return $this
                ->addUsingAlias(UserPeer::ID, $fullReviewOpinionLog->getUserIdTo(), $comparison);
        } elseif ($fullReviewOpinionLog instanceof PropelObjectCollection) {
            return $this
                ->useFullReviewOpinionLogRelatedByUserIdToQuery()
                ->filterByPrimaryKeys($fullReviewOpinionLog->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFullReviewOpinionLogRelatedByUserIdTo() only accepts arguments of type FullReviewOpinionLog or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FullReviewOpinionLogRelatedByUserIdTo relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinFullReviewOpinionLogRelatedByUserIdTo($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FullReviewOpinionLogRelatedByUserIdTo');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FullReviewOpinionLogRelatedByUserIdTo');
        }

        return $this;
    }

    /**
     * Use the FullReviewOpinionLogRelatedByUserIdTo relation FullReviewOpinionLog object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\FullReviewOpinionLogQuery A secondary query class using the current class as primary query
     */
    public function useFullReviewOpinionLogRelatedByUserIdToQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFullReviewOpinionLogRelatedByUserIdTo($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FullReviewOpinionLogRelatedByUserIdTo', '\GOEDCSD\CommonBundle\Model\FullReviewOpinionLogQuery');
    }

    /**
     * Filter the query by a related FullReviewOpinionApproval object
     *
     * @param   FullReviewOpinionApproval|PropelObjectCollection $fullReviewOpinionApproval  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFullReviewOpinionApproval($fullReviewOpinionApproval, $comparison = null)
    {
        if ($fullReviewOpinionApproval instanceof FullReviewOpinionApproval) {
            return $this
                ->addUsingAlias(UserPeer::ID, $fullReviewOpinionApproval->getUserId(), $comparison);
        } elseif ($fullReviewOpinionApproval instanceof PropelObjectCollection) {
            return $this
                ->useFullReviewOpinionApprovalQuery()
                ->filterByPrimaryKeys($fullReviewOpinionApproval->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFullReviewOpinionApproval() only accepts arguments of type FullReviewOpinionApproval or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FullReviewOpinionApproval relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinFullReviewOpinionApproval($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FullReviewOpinionApproval');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FullReviewOpinionApproval');
        }

        return $this;
    }

    /**
     * Use the FullReviewOpinionApproval relation FullReviewOpinionApproval object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\FullReviewOpinionApprovalQuery A secondary query class using the current class as primary query
     */
    public function useFullReviewOpinionApprovalQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFullReviewOpinionApproval($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FullReviewOpinionApproval', '\GOEDCSD\CommonBundle\Model\FullReviewOpinionApprovalQuery');
    }

    /**
     * Filter the query by a related GroupOpinion object
     *
     * @param   GroupOpinion|PropelObjectCollection $groupOpinion  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByGroupOpinion($groupOpinion, $comparison = null)
    {
        if ($groupOpinion instanceof GroupOpinion) {
            return $this
                ->addUsingAlias(UserPeer::ID, $groupOpinion->getUserId(), $comparison);
        } elseif ($groupOpinion instanceof PropelObjectCollection) {
            return $this
                ->useGroupOpinionQuery()
                ->filterByPrimaryKeys($groupOpinion->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByGroupOpinion() only accepts arguments of type GroupOpinion or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GroupOpinion relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinGroupOpinion($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GroupOpinion');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'GroupOpinion');
        }

        return $this;
    }

    /**
     * Use the GroupOpinion relation GroupOpinion object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\GroupOpinionQuery A secondary query class using the current class as primary query
     */
    public function useGroupOpinionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinGroupOpinion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GroupOpinion', '\GOEDCSD\CommonBundle\Model\GroupOpinionQuery');
    }

    /**
     * Filter the query by a related InitialReviewOpinion object
     *
     * @param   InitialReviewOpinion|PropelObjectCollection $initialReviewOpinion  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByInitialReviewOpinion($initialReviewOpinion, $comparison = null)
    {
        if ($initialReviewOpinion instanceof InitialReviewOpinion) {
            return $this
                ->addUsingAlias(UserPeer::ID, $initialReviewOpinion->getUserId(), $comparison);
        } elseif ($initialReviewOpinion instanceof PropelObjectCollection) {
            return $this
                ->useInitialReviewOpinionQuery()
                ->filterByPrimaryKeys($initialReviewOpinion->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInitialReviewOpinion() only accepts arguments of type InitialReviewOpinion or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InitialReviewOpinion relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinInitialReviewOpinion($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InitialReviewOpinion');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'InitialReviewOpinion');
        }

        return $this;
    }

    /**
     * Use the InitialReviewOpinion relation InitialReviewOpinion object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\InitialReviewOpinionQuery A secondary query class using the current class as primary query
     */
    public function useInitialReviewOpinionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInitialReviewOpinion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InitialReviewOpinion', '\GOEDCSD\CommonBundle\Model\InitialReviewOpinionQuery');
    }

    /**
     * Filter the query by a related InitialReviewOpinionLog object
     *
     * @param   InitialReviewOpinionLog|PropelObjectCollection $initialReviewOpinionLog  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByInitialReviewOpinionLogRelatedByUserIdFrom($initialReviewOpinionLog, $comparison = null)
    {
        if ($initialReviewOpinionLog instanceof InitialReviewOpinionLog) {
            return $this
                ->addUsingAlias(UserPeer::ID, $initialReviewOpinionLog->getUserIdFrom(), $comparison);
        } elseif ($initialReviewOpinionLog instanceof PropelObjectCollection) {
            return $this
                ->useInitialReviewOpinionLogRelatedByUserIdFromQuery()
                ->filterByPrimaryKeys($initialReviewOpinionLog->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInitialReviewOpinionLogRelatedByUserIdFrom() only accepts arguments of type InitialReviewOpinionLog or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InitialReviewOpinionLogRelatedByUserIdFrom relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinInitialReviewOpinionLogRelatedByUserIdFrom($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InitialReviewOpinionLogRelatedByUserIdFrom');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'InitialReviewOpinionLogRelatedByUserIdFrom');
        }

        return $this;
    }

    /**
     * Use the InitialReviewOpinionLogRelatedByUserIdFrom relation InitialReviewOpinionLog object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\InitialReviewOpinionLogQuery A secondary query class using the current class as primary query
     */
    public function useInitialReviewOpinionLogRelatedByUserIdFromQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInitialReviewOpinionLogRelatedByUserIdFrom($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InitialReviewOpinionLogRelatedByUserIdFrom', '\GOEDCSD\CommonBundle\Model\InitialReviewOpinionLogQuery');
    }

    /**
     * Filter the query by a related InitialReviewOpinionLog object
     *
     * @param   InitialReviewOpinionLog|PropelObjectCollection $initialReviewOpinionLog  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByInitialReviewOpinionLogRelatedByUserIdTo($initialReviewOpinionLog, $comparison = null)
    {
        if ($initialReviewOpinionLog instanceof InitialReviewOpinionLog) {
            return $this
                ->addUsingAlias(UserPeer::ID, $initialReviewOpinionLog->getUserIdTo(), $comparison);
        } elseif ($initialReviewOpinionLog instanceof PropelObjectCollection) {
            return $this
                ->useInitialReviewOpinionLogRelatedByUserIdToQuery()
                ->filterByPrimaryKeys($initialReviewOpinionLog->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInitialReviewOpinionLogRelatedByUserIdTo() only accepts arguments of type InitialReviewOpinionLog or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InitialReviewOpinionLogRelatedByUserIdTo relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinInitialReviewOpinionLogRelatedByUserIdTo($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InitialReviewOpinionLogRelatedByUserIdTo');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'InitialReviewOpinionLogRelatedByUserIdTo');
        }

        return $this;
    }

    /**
     * Use the InitialReviewOpinionLogRelatedByUserIdTo relation InitialReviewOpinionLog object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\InitialReviewOpinionLogQuery A secondary query class using the current class as primary query
     */
    public function useInitialReviewOpinionLogRelatedByUserIdToQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInitialReviewOpinionLogRelatedByUserIdTo($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InitialReviewOpinionLogRelatedByUserIdTo', '\GOEDCSD\CommonBundle\Model\InitialReviewOpinionLogQuery');
    }

    /**
     * Filter the query by a related MeasurementOpinion object
     *
     * @param   MeasurementOpinion|PropelObjectCollection $measurementOpinion  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByMeasurementOpinion($measurementOpinion, $comparison = null)
    {
        if ($measurementOpinion instanceof MeasurementOpinion) {
            return $this
                ->addUsingAlias(UserPeer::ID, $measurementOpinion->getUserId(), $comparison);
        } elseif ($measurementOpinion instanceof PropelObjectCollection) {
            return $this
                ->useMeasurementOpinionQuery()
                ->filterByPrimaryKeys($measurementOpinion->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMeasurementOpinion() only accepts arguments of type MeasurementOpinion or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MeasurementOpinion relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinMeasurementOpinion($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MeasurementOpinion');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'MeasurementOpinion');
        }

        return $this;
    }

    /**
     * Use the MeasurementOpinion relation MeasurementOpinion object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\MeasurementOpinionQuery A secondary query class using the current class as primary query
     */
    public function useMeasurementOpinionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMeasurementOpinion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MeasurementOpinion', '\GOEDCSD\CommonBundle\Model\MeasurementOpinionQuery');
    }

    /**
     * Filter the query by a related PMQuery object
     *
     * @param   PMQuery|PropelObjectCollection $pMQuery  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPMQueryRelatedByScientistId($pMQuery, $comparison = null)
    {
        if ($pMQuery instanceof PMQuery) {
            return $this
                ->addUsingAlias(UserPeer::ID, $pMQuery->getScientistId(), $comparison);
        } elseif ($pMQuery instanceof PropelObjectCollection) {
            return $this
                ->usePMQueryRelatedByScientistIdQuery()
                ->filterByPrimaryKeys($pMQuery->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPMQueryRelatedByScientistId() only accepts arguments of type PMQuery or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PMQueryRelatedByScientistId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinPMQueryRelatedByScientistId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PMQueryRelatedByScientistId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PMQueryRelatedByScientistId');
        }

        return $this;
    }

    /**
     * Use the PMQueryRelatedByScientistId relation PMQuery object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\PMQueryQuery A secondary query class using the current class as primary query
     */
    public function usePMQueryRelatedByScientistIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPMQueryRelatedByScientistId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PMQueryRelatedByScientistId', '\GOEDCSD\CommonBundle\Model\PMQueryQuery');
    }

    /**
     * Filter the query by a related PMQuery object
     *
     * @param   PMQuery|PropelObjectCollection $pMQuery  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPMQueryRelatedByDataEntry1Id($pMQuery, $comparison = null)
    {
        if ($pMQuery instanceof PMQuery) {
            return $this
                ->addUsingAlias(UserPeer::ID, $pMQuery->getDataEntry1Id(), $comparison);
        } elseif ($pMQuery instanceof PropelObjectCollection) {
            return $this
                ->usePMQueryRelatedByDataEntry1IdQuery()
                ->filterByPrimaryKeys($pMQuery->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPMQueryRelatedByDataEntry1Id() only accepts arguments of type PMQuery or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PMQueryRelatedByDataEntry1Id relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinPMQueryRelatedByDataEntry1Id($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PMQueryRelatedByDataEntry1Id');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PMQueryRelatedByDataEntry1Id');
        }

        return $this;
    }

    /**
     * Use the PMQueryRelatedByDataEntry1Id relation PMQuery object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\PMQueryQuery A secondary query class using the current class as primary query
     */
    public function usePMQueryRelatedByDataEntry1IdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPMQueryRelatedByDataEntry1Id($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PMQueryRelatedByDataEntry1Id', '\GOEDCSD\CommonBundle\Model\PMQueryQuery');
    }

    /**
     * Filter the query by a related PMQuery object
     *
     * @param   PMQuery|PropelObjectCollection $pMQuery  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPMQueryRelatedByDataEntry2Id($pMQuery, $comparison = null)
    {
        if ($pMQuery instanceof PMQuery) {
            return $this
                ->addUsingAlias(UserPeer::ID, $pMQuery->getDataEntry2Id(), $comparison);
        } elseif ($pMQuery instanceof PropelObjectCollection) {
            return $this
                ->usePMQueryRelatedByDataEntry2IdQuery()
                ->filterByPrimaryKeys($pMQuery->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPMQueryRelatedByDataEntry2Id() only accepts arguments of type PMQuery or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PMQueryRelatedByDataEntry2Id relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinPMQueryRelatedByDataEntry2Id($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PMQueryRelatedByDataEntry2Id');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PMQueryRelatedByDataEntry2Id');
        }

        return $this;
    }

    /**
     * Use the PMQueryRelatedByDataEntry2Id relation PMQuery object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\PMQueryQuery A secondary query class using the current class as primary query
     */
    public function usePMQueryRelatedByDataEntry2IdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPMQueryRelatedByDataEntry2Id($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PMQueryRelatedByDataEntry2Id', '\GOEDCSD\CommonBundle\Model\PMQueryQuery');
    }

    /**
     * Filter the query by a related TreatmentOpinion object
     *
     * @param   TreatmentOpinion|PropelObjectCollection $treatmentOpinion  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTreatmentOpinion($treatmentOpinion, $comparison = null)
    {
        if ($treatmentOpinion instanceof TreatmentOpinion) {
            return $this
                ->addUsingAlias(UserPeer::ID, $treatmentOpinion->getUserId(), $comparison);
        } elseif ($treatmentOpinion instanceof PropelObjectCollection) {
            return $this
                ->useTreatmentOpinionQuery()
                ->filterByPrimaryKeys($treatmentOpinion->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTreatmentOpinion() only accepts arguments of type TreatmentOpinion or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the TreatmentOpinion relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinTreatmentOpinion($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('TreatmentOpinion');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'TreatmentOpinion');
        }

        return $this;
    }

    /**
     * Use the TreatmentOpinion relation TreatmentOpinion object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\TreatmentOpinionQuery A secondary query class using the current class as primary query
     */
    public function useTreatmentOpinionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTreatmentOpinion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'TreatmentOpinion', '\GOEDCSD\CommonBundle\Model\TreatmentOpinionQuery');
    }

    /**
     * Filter the query by a related TimepointOpinion object
     *
     * @param   TimepointOpinion|PropelObjectCollection $timepointOpinion  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTimepointOpinion($timepointOpinion, $comparison = null)
    {
        if ($timepointOpinion instanceof TimepointOpinion) {
            return $this
                ->addUsingAlias(UserPeer::ID, $timepointOpinion->getUserId(), $comparison);
        } elseif ($timepointOpinion instanceof PropelObjectCollection) {
            return $this
                ->useTimepointOpinionQuery()
                ->filterByPrimaryKeys($timepointOpinion->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTimepointOpinion() only accepts arguments of type TimepointOpinion or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the TimepointOpinion relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinTimepointOpinion($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('TimepointOpinion');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'TimepointOpinion');
        }

        return $this;
    }

    /**
     * Use the TimepointOpinion relation TimepointOpinion object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\TimepointOpinionQuery A secondary query class using the current class as primary query
     */
    public function useTimepointOpinionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinTimepointOpinion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'TimepointOpinion', '\GOEDCSD\CommonBundle\Model\TimepointOpinionQuery');
    }

    /**
     * Filter the query by a related UserDetails object
     *
     * @param   UserDetails|PropelObjectCollection $userDetails  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUserDetails($userDetails, $comparison = null)
    {
        if ($userDetails instanceof UserDetails) {
            return $this
                ->addUsingAlias(UserPeer::ID, $userDetails->getUserId(), $comparison);
        } elseif ($userDetails instanceof PropelObjectCollection) {
            return $this
                ->useUserDetailsQuery()
                ->filterByPrimaryKeys($userDetails->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserDetails() only accepts arguments of type UserDetails or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserDetails relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinUserDetails($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserDetails');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'UserDetails');
        }

        return $this;
    }

    /**
     * Use the UserDetails relation UserDetails object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GOEDCSD\CommonBundle\Model\UserDetailsQuery A secondary query class using the current class as primary query
     */
    public function useUserDetailsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserDetails($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserDetails', '\GOEDCSD\CommonBundle\Model\UserDetailsQuery');
    }

    /**
     * Filter the query by a related SecurityRole object
     * using the roles_users table as cross reference
     *
     * @param   SecurityRole $securityRole the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuery The current query, for fluid interface
     */
    public function filterBySecurityRole($securityRole, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useRoleUserQuery()
            ->filterBySecurityRole($securityRole, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   User $user Object to remove from the list of results
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserPeer::ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
