<?php

namespace Dayspring\LoginBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'users' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.LoginBundle.map
 */
class UserTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'LoginBundle.map.UserTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('users');
        $this->setPhpName('User');
        $this->setClassname('Dayspring\\LoginBundle\\Model\\User');
        $this->setPackage('LoginBundle');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('email', 'Email', 'VARCHAR', false, 100, null);
        $this->addColumn('password', 'Password', 'VARCHAR', false, 100, null);
        $this->addColumn('salt', 'Salt', 'VARCHAR', false, 100, null);
        $this->addColumn('reset_token', 'ResetToken', 'CHAR', false, 40, null);
        $this->addColumn('reset_token_expire', 'ResetTokenExpire', 'TIMESTAMP', false, null, null);
        $this->addColumn('created_date', 'CreatedDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('last_login_date', 'LastLoginDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('is_active', 'IsActive', 'BOOLEAN', true, 1, true);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('RoleUser', 'Dayspring\\LoginBundle\\Model\\RoleUser', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), null, null, 'RoleUsers');
        $this->addRelation('SecurityRole', 'Dayspring\\LoginBundle\\Model\\SecurityRole', RelationMap::MANY_TO_MANY, array(), null, null, 'SecurityRoles');
    } // buildRelations()

} // UserTableMap
