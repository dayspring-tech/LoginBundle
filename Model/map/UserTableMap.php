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
 * @package    propel.generator.vendor.dayspring-tech.login-bundle.Model.map
 */
class UserTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'vendor.dayspring-tech.login-bundle.Model.map.UserTableMap';

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
        $this->setPackage('vendor.dayspring-tech.login-bundle.Model');
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
        $this->addRelation('ArticleOpinion', 'GOEDCSD\\CommonBundle\\Model\\ArticleOpinion', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), null, null, 'ArticleOpinions');
        $this->addRelation('AuditLog', 'GOEDCSD\\CommonBundle\\Model\\AuditLog', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), null, null, 'AuditLogs');
        $this->addRelation('BaselineStateTableMetadata', 'GOEDCSD\\CommonBundle\\Model\\BaselineStateTableMetadata', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), null, null, 'BaselineStateTableMetadatas');
        $this->addRelation('FullReviewOpinion', 'GOEDCSD\\CommonBundle\\Model\\FullReviewOpinion', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), null, null, 'FullReviewOpinions');
        $this->addRelation('FullReviewOpinionLogRelatedByUserIdFrom', 'GOEDCSD\\CommonBundle\\Model\\FullReviewOpinionLog', RelationMap::ONE_TO_MANY, array('id' => 'user_id_from', ), null, null, 'FullReviewOpinionLogsRelatedByUserIdFrom');
        $this->addRelation('FullReviewOpinionLogRelatedByUserIdTo', 'GOEDCSD\\CommonBundle\\Model\\FullReviewOpinionLog', RelationMap::ONE_TO_MANY, array('id' => 'user_id_to', ), null, null, 'FullReviewOpinionLogsRelatedByUserIdTo');
        $this->addRelation('FullReviewOpinionApproval', 'GOEDCSD\\CommonBundle\\Model\\FullReviewOpinionApproval', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), null, null, 'FullReviewOpinionApprovals');
        $this->addRelation('GroupOpinion', 'GOEDCSD\\CommonBundle\\Model\\GroupOpinion', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), null, null, 'GroupOpinions');
        $this->addRelation('InitialReviewOpinion', 'GOEDCSD\\CommonBundle\\Model\\InitialReviewOpinion', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), null, null, 'InitialReviewOpinions');
        $this->addRelation('InitialReviewOpinionLogRelatedByUserIdFrom', 'GOEDCSD\\CommonBundle\\Model\\InitialReviewOpinionLog', RelationMap::ONE_TO_MANY, array('id' => 'user_id_from', ), null, null, 'InitialReviewOpinionLogsRelatedByUserIdFrom');
        $this->addRelation('InitialReviewOpinionLogRelatedByUserIdTo', 'GOEDCSD\\CommonBundle\\Model\\InitialReviewOpinionLog', RelationMap::ONE_TO_MANY, array('id' => 'user_id_to', ), null, null, 'InitialReviewOpinionLogsRelatedByUserIdTo');
        $this->addRelation('MeasurementOpinion', 'GOEDCSD\\CommonBundle\\Model\\MeasurementOpinion', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), null, null, 'MeasurementOpinions');
        $this->addRelation('PMQueryRelatedByScientistId', 'GOEDCSD\\CommonBundle\\Model\\PMQuery', RelationMap::ONE_TO_MANY, array('id' => 'scientist_id', ), null, null, 'PMQueriesRelatedByScientistId');
        $this->addRelation('PMQueryRelatedByDataEntry1Id', 'GOEDCSD\\CommonBundle\\Model\\PMQuery', RelationMap::ONE_TO_MANY, array('id' => 'data_entry1_id', ), null, null, 'PMQueriesRelatedByDataEntry1Id');
        $this->addRelation('PMQueryRelatedByDataEntry2Id', 'GOEDCSD\\CommonBundle\\Model\\PMQuery', RelationMap::ONE_TO_MANY, array('id' => 'data_entry2_id', ), null, null, 'PMQueriesRelatedByDataEntry2Id');
        $this->addRelation('TreatmentOpinion', 'GOEDCSD\\CommonBundle\\Model\\TreatmentOpinion', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), null, null, 'TreatmentOpinions');
        $this->addRelation('TimepointOpinion', 'GOEDCSD\\CommonBundle\\Model\\TimepointOpinion', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), null, null, 'TimepointOpinions');
        $this->addRelation('UserDetails', 'GOEDCSD\\CommonBundle\\Model\\UserDetails', RelationMap::ONE_TO_ONE, array('id' => 'user_id', ), 'CASCADE', null);
        $this->addRelation('SecurityRole', 'Dayspring\\LoginBundle\\Model\\SecurityRole', RelationMap::MANY_TO_MANY, array(), null, null, 'SecurityRoles');
    } // buildRelations()

} // UserTableMap
