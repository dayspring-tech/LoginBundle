<?php

namespace Dayspring\LoginBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'roles_users' table.
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
class RoleUserTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'LoginBundle.map.RoleUserTableMap';

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
        $this->setName('roles_users');
        $this->setPhpName('RoleUser');
        $this->setClassname('Dayspring\\LoginBundle\\Model\\RoleUser');
        $this->setPackage('LoginBundle');
        $this->setUseIdGenerator(false);
        $this->setIsCrossRef(true);
        // columns
        $this->addForeignPrimaryKey('user_id', 'UserId', 'INTEGER' , 'users', 'id', true, null, null);
        $this->addForeignPrimaryKey('role_id', 'RoleId', 'INTEGER' , 'roles', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'Dayspring\\LoginBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), null, null);
        $this->addRelation('SecurityRole', 'Dayspring\\LoginBundle\\Model\\SecurityRole', RelationMap::MANY_TO_ONE, array('role_id' => 'id', ), null, null);
    } // buildRelations()

} // RoleUserTableMap
