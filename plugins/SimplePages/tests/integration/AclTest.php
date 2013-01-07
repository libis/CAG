<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2007-2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package SimplePages
 */

/**
 * 
 *
 * @package SimplePages
 * @copyright Center for History and New Media, 2007-2010
 */
class SimplePages_AclTest extends Omeka_Test_AppTestCase
{
    const PAGE_RESOURCE = 'SimplePages_Page';
    const ADMIN_RESOURCE = 'SimplePages_Index';
    
    public function setUp()
    {
        parent::setUp();
        require_once PLUGIN_DIR . DIRECTORY_SEPARATOR . 'SimplePages' 
            . DIRECTORY_SEPARATOR . 'plugin.php';
        simple_pages_define_acl($this->acl);    
    }
    
    public function assertPreConditions()
    {
        if (version_compare(OMEKA_VERSION, '2.0-dev', '<')) {
            $this->assertFalse($this->currentuser);
        } else {
            $this->assertNull($this->currentuser);
        }
        $this->assertTrue($this->acl->has('SimplePages_Index'),
            "SimplePages ACL resources have not been defined.");
    }
    
    public function testNonauthenticatedUsersCanViewPublishedPages()
    {
        $this->assertTrue($this->acl->isAllowed(null, self::PAGE_RESOURCE, 'show'));
    }
    
    public function testNonauthenticatedUsersCannotViewUnpublishedPages()
    {
        $this->assertFalse($this->acl->isAllowed(null, self::PAGE_RESOURCE, 'show-unpublished'));
    }
    
    public function testNonauthenticatedUsersCannotEditPages()
    {
        $this->assertFalse($this->acl->isAllowed(null, self::ADMIN_RESOURCE, 'edit'));
    }
    
    public function testContributorsCannotEditPages()
    {
        $this->assertFalse($this->acl->isAllowed('contributor', self::ADMIN_RESOURCE, 'edit'));
    }
    
    public function testAdminsCanEditPages()
    {
        $this->assertTrue($this->acl->isAllowed('admin', self::ADMIN_RESOURCE, 'edit'));
    }
    
    public function testSuperUsersCanEditPages()
    {
        $this->assertTrue($this->acl->isAllowed('super', self::ADMIN_RESOURCE, 'edit'));        
    }
}