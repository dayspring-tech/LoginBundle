<?php

namespace Dayspring\LoginBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'roles' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator..map
 */
class SecurityRoleTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.map.SecurityRoleTableMap';

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
        $this->setName('roles');
        $this->setPhpName('SecurityRole');
        $this->setClassname('Dayspring\\LoginBundle\\Model\\SecurityRole');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('role_name', 'RoleName', 'VARCHAR', true, 50, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('RoleUser', 'Dayspring\\LoginBundle\\Model\\RoleUser', RelationMap::ONE_TO_MANY, array('id' => 'role_id', ), null, null, 'RoleUsers');
        $this->addRelation('User', 'Dayspring\\LoginBundle\\Model\\User', RelationMap::MANY_TO_MANY, array(), null, null, 'Users');
    } // buildRelations()

} // SecurityRoleTableMap
