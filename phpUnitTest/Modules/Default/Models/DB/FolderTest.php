<?php

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-05-27 at 15:49:13.
 */
class FolderTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Folder
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_tearDown();
        $u = new \User;
        if(!$u->count(1))
            $u->Join("b.g.dariush@gmail.com", "123");
        $this->object = new Folder;
        $this->assertCount(2, User::all());
    }
    
    public function testAll()
    {
        $this->_tearDown();
        // echo  "\r\n".date('s:m');
        $this->_testCreateFolder();
        $this->_testGetRoots();
        $this->_testGetChildren();
        $this->_testGetByParentId();
        $this->_testGetByName();
        $this->_testGetByUserId();
        // echo  "\r\n".date('s:m');
        $this->_testRemoveSubfolder();
        $this->_tearDown();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function _tearDown()
    {
        Folder::query("SET FOREIGN_KEY_CHECKS=0");
        //Folder::query("TRUNCATE TABLE `users`");
        Folder::query("TRUNCATE TABLE `folders`");
        $f = new Folder();
        $f->folder_id = Folder::ROOT_PARENT;
        $f->parent_id = Folder::ROOT_PARENT;
        $f->user_id = Folder::ROOT_PARENT;
        $f->folder_name = "ROOT";
        $f->save();
        Folder::query("SET FOREIGN_KEY_CHECKS=1");
        $this->assertcount(1,Folder::all());
        //$this->assertcount(0,User::all());
    }
    
    public function _testRemoveSubfolder()
    {
        // echo  "\r\n".date('s:m');
        $this->_testCreateFolder();
        // echo  "\r\n".date('s:m');
        // check folders existance
        $this->assertNotNull(Folder::GetFolder(1,2));
        $this->assertNotNull(Folder::GetFolder(1,3));
        $this->assertNotNull(Folder::GetFolder(1,4));
        // echo  "\r\n".date('s:m');
        /**
         * Remove folders & subfolders 1-by-1
         */
        $this->assertEquals(1, Folder::Remove(1, 3));
        $this->assertEquals(2, Folder::Remove(1,2));
        // echo  "\r\n".date('s:m');
        $this->_testCreateFolder();
        // echo  "\r\n".date('s:m');
        $this->assertEquals(1, Folder::Remove(1, 4));
        $this->assertEquals(2, Folder::Remove(1,2));
        // echo  "\r\n".date('s:m');
        $this->_testCreateFolder();
        // echo  "\r\n".date('s:m');
        $this->assertEquals(3, Folder::Remove(1,2));
        // echo  "\r\n".date('s:m');
    }

    public function _testInital()
    {
        $this->assertCount(0,  Folder::all());
        $this->assertFalse(Folder::exists(1));
    }
    /**
     * @covers Folder::GetFolder
     * @todo   Implement testGetByUserId().
     */
    public function _testGetByUserId() {
        try{
            // fake user id
            Folder::GetFolder(1, -1);
        }  catch (Exception $e){}
        $this->assertNotNull($e);
        try{
            $e=null;
            // real user id
            Folder::GetFolder(1, 2);
        }  catch (Exception $e){/* echo  "\r\n {$e->getMessage()}";*/}
        $this->assertNull($e);
    }
    /**
     * @covers Folder::GetByParentId
     * @todo   Implement testGetByParentId().
     */
    public function _testGetByParentId() {
        $uid = $this->object->GetByParentId(1,2);
        $this->assertCount(2, $uid);
        $uid = $this->object->GetByParentId(1, -1);
        $this->assertCount(1, $uid);
    }

    /**
     * @covers Folder::GetByName
     * @todo   Implement testGetByName().
     */
    public function _testGetByName() {
        $u = $this->object->GetByName(1, 2, "dariush");
        $this->assertNull($u);
        $u = $this->object->GetByName(1, 2, "\$folder_name_child_1");
        $this->assertNotNull($u);
        $this->assertEquals($u->folder_name, "\$folder_name_child_1");
    }

    /**
     * @covers Folder::CreateFolder
     * @todo   Implement testCreateFolder().
     */
    public function _testCreateFolder() {
        $this->setUp();
        $this->assertEquals(1,count(Folder::find(-1)));
        $this->assertFalse(Folder::exists(1));
        // create with fake user_id
        $this->assertFalse($this->CreateFolder(123, 1, "\$folder_name"));
        // create with fake parent_id
        $this->assertFalse($this->CreateFolder(1, -123, "\$folder_name"));
        // create a cool one
        $this->assertTrue($this->CreateFolder(1, Folder::ROOT_PARENT, "\$folder_name"));
        // create a cool child one 
        $this->assertTrue($this->CreateFolder(1, 2, "\$folder_name_child_1"));
        // create an other cool child one 
        $this->assertTrue($this->CreateFolder(1, 2, "\$folder_name_child_2"));
        // create a duplicate child one 
        $this->assertFalse($this->CreateFolder(1, 2, "\$folder_name_child_1"));
    }

    /**
     * @covers Folder::GetRoots
     * @todo   Implement testGetRoots().
     */
    public function _testGetRoots() 
    {
        $r = Folder::GetRoots(1);
        $this->assertCount(1, $r);
        $t = $r[0];
        $this->assertEquals($t->folder_name, "\$folder_name");
        // create a cool one
        $this->assertTrue($this->CreateFolder(1, Folder::ROOT_PARENT, "\$another_folder_name"));        
        $r = $this->object->GetRoots(1);
        $this->assertCount(2, $r);
        $t = $r[0];
        $this->assertEquals($t->folder_name, "\$another_folder_name");
        $this->assertEquals($t->folder_id, 6);
        $t = $r[1];
        $this->assertEquals($t->folder_name, "\$folder_name");  
        $f=Folder::GetByName(1, Folder::ROOT_PARENT, "\$another_folder_name");
        $this->assertTrue(1==Folder::Remove(1, $f->folder_id));
        $this->assertCount(1, Folder::GetRoots(1));
    }

    public function CreateFolder($user_id, $parent_id, $name)
    {
        static $counter;
        
        $counter = isset($counter)?$counter+1:0;
        
        $count = count(Folder::all());
        //\iMVC\Tools\Debug::_var(array('$user_id'=>$user_id,'$parent_id'=>$parent_id,'$name'=>$name));
        try{
            // create with user & parent_id with a name
            Folder::CreateFolder($user_id, $parent_id, $name);
        }catch(Exception $e){ /*echo  "\r\n#$counter ".$e->getMessage()."\r\n"; */return false; }
        
        $this->assertFalse(isset($e));
        
        $this->assertCount($count + 1, Folder::all());
        
        return true;
    }
    
    /**
     * @covers Folder::GetChildren
     * @todo   Implement testGetChildren().
     */
    public function _testGetChildren() {
        $c = $this->object->GetChildren(1, 2);
        $this->assertCount(2, $c);
        for($i=0;$i<count($c);$i++)
        {
            $j=$c[$i];
            $s = "\$folder_name_child_".($i+1);
            $this->assertEquals($s,  $j->folder_name);
        }
    }

    /**
     * @covers Folder::validate_user_existance
     * @todo   Implement testValidate_user_existance().
     */
    public function _testValidate_user_existance() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Folder::validate_parent_existance
     * @todo   Implement testValidate_parent_existance().
     */
    public function _testValidate_parent_existance() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}
