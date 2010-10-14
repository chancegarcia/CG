<?php
/**
 * Storage Interface
 * @category   My
 * @version 1.0
 * @author chancegarcia.com
 * @license http://www.opensource.org/licenses/lgpl-3.0.html
 */
 
interface My_Storage
{
    public function browse();
    public function read($id=null);
    public function edit($data=null);
    public function add($data=null);
    public function delete($id=null);
}
