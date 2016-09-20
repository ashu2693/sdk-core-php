<?php

/*
 * Copyright 2016 MasterCard International.
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list of
 * conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution.
 * Neither the name of the MasterCard International Incorporated nor the names of its
 * contributors may be used to endorse or promote products derived from this software
 * without specific prior written permission.
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT
 * SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
 * TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS;
 * OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER
 * IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING
 * IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 *
 */


namespace MasterCard\Core\Model;

use MasterCard\Core\Model\RequestMap;

class BaseMapTest extends \PHPUnit_Framework_TestCase
{
    public function testMap()
    {
        $baseObject = new RequestMap();
        $baseObject->set("key1", "value1");
        
        $this->assertTrue($baseObject != NULL);
        $this->assertTrue($baseObject->containsKey("key1"));
        $this->assertEquals(1, $baseObject->size());
        $this->assertEquals("value1", $baseObject->get("key1"));
    }
    
    
    public function testNestedMap()
    {
        $baseObject = new RequestMap();
        $baseObject->set("key1.key2.key3", "value1");
        $baseObject->set("key1.key2.key4", "value2");
        
        
        $this->assertTrue($baseObject != NULL);
        
        $this->assertTrue($baseObject->containsKey("key1"));
        $this->assertTrue($baseObject->containsKey("key1.key2"));
        $this->assertTrue($baseObject->containsKey("key1.key2.key3"));
        $this->assertTrue($baseObject->size() == 1);
        $this->assertEquals("value1", $baseObject->get("key1.key2.key3"));
        
        $this->assertTrue($baseObject->containsKey("key1.key2.key4"));
        $this->assertEquals("value2", $baseObject->get("key1.key2.key4"));
        
        
        $this->assertFalse($baseObject->containsKey("key1.key2.key3."));
        $this->assertNull($baseObject->get("key1.key2.key3."));
        $this->assertFalse($baseObject->containsKey("key1.key2."));
        $this->assertNull($baseObject->get("key1.key2."));
        $this->assertFalse($baseObject->containsKey("key1."));
        $this->assertNull($baseObject->get("key1."));
        
        $this->assertFalse($baseObject->containsKey("key1.key2.something.different"));
        $this->assertNull($baseObject->get("key1.key2.something.different"));
        $this->assertFalse($baseObject->containsKey("key1.something.different"));
        $this->assertNull($baseObject->get("key1.something.different"));
        $this->assertFalse($baseObject->containsKey("key1..."));
        $this->assertNull($baseObject->get("key1..."));           
        
    }
    
        public function testNestedMapWithList()
    {
        $body = "[ { \"user.name\":\"andrea\", \"user.surname\":\"rizzini\" } ]";
        $baseMap = new RequestMap();
        $baseMap->setAll(json_decode($body, true));
        
        $this->assertEquals(1, $baseMap->size());
        $this->assertEquals("andrea", $baseMap->get("list[0].user.name"));
        $this->assertEquals("rizzini", $baseMap->get("list[0].user.surname"));
        
        
        $this->assertTrue($baseMap->containsKey("list"));
        $this->assertNotEmpty($baseMap->get("list"));
        $this->assertTrue($baseMap->containsKey("list[0]"));
        $this->assertNotEmpty($baseMap->get("list[0]"));
        $this->assertTrue($baseMap->containsKey("list[0].user"));
        $this->assertNotEmpty($baseMap->get("list[0].user"));
        $this->assertTrue($baseMap->containsKey("list[0].user.name"));
        $this->assertNotEmpty($baseMap->get("list[0].user.name"));
        $this->assertTrue($baseMap->containsKey("list[]"));
        $this->assertNotEmpty($baseMap->get("list[]"));
        $this->assertTrue($baseMap->containsKey("list[].user"));
        $this->assertNotEmpty($baseMap->get("list[].user"));
        $this->assertTrue($baseMap->containsKey("list[0].user.name"));
        $this->assertNotEmpty($baseMap->get("list[0].user.name"));
        
        $this->assertFalse($baseMap->containsKey("list2"));
        $this->assertEmpty($baseMap->get("list2"));
        $this->assertFalse($baseMap->containsKey("list[2]"));
        $this->assertEmpty($baseMap->get("list[2]"));
        
        $this->assertFalse($baseMap->containsKey("list[2].some.nonsense"));
        $this->assertEmpty($baseMap->get("list[2].some.nonsense"));
        $this->assertFalse($baseMap->containsKey("list[2].some...."));
        $this->assertEmpty($baseMap->get("list[2].some...."));
        
    }
    
    public function TestNestedMapFromSetMethod() {
        $map = new RequestMap();
        $map->set("payment_transfer.transfer_reference", "10010920928521687681493");
        $map->set("payment_transfer.payment_type", "P2P");
        $map->set("payment_transfer.amount", "1000");
        $map->set("payment_transfer.currency", "USD");
        $map->set("payment_transfer.sender_account_uri", "pan:5013040000000018;exp=2017-08;cvc=123");
        $map->set("payment_transfer.sender.first_name", "John");
        $map->set("payment_transfer.sender.middle_name", "Tyler");
        $map->set("payment_transfer.sender.last_name", "Jones");
        $map->set("payment_transfer.sender.nationality", "USA");
        $map->set("payment_transfer.sender.date_of_birth", "1994-05-21");
        $map->set("payment_transfer.sender.address.line1", "21 Broadway");
        $map->set("payment_transfer.sender.address.line2", "Apartment A-6");
        $map->set("payment_transfer.sender.address.city", "OFallon");
        $map->set("payment_transfer.sender.address.country_subdivision", "MO");
        $map->set("payment_transfer.sender.address.postal_code", "63368");
        $map->set("payment_transfer.sender.address.country", "USA");
        $map->set("payment_transfer.sender.phone", "11234565555");
        $map->set("payment_transfer.sender.email", " John.Jones123@abcmail.com ");
        $map->set("payment_transfer.recipient_account_uri", "pan:5013040000000018;exp=2017-08;cvc=123");
        $map->set("payment_transfer.recipient.first_name", "Jane");
        $map->set("payment_transfer.recipient.middle_name", "Tyler");
        $map->set("payment_transfer.recipient.last_name", "Smith");
        $map->set("payment_transfer.recipient.nationality", "USA");
        $map->set("payment_transfer.recipient.date_of_birth", "1999-12-30");
        $map->set("payment_transfer.recipient.address.line1", "1 Main St");
        $map->set("payment_transfer.recipient.address.line2", "Apartment 9");
        $map->set("payment_transfer.recipient.address.city", "OFallon");
        $map->set("payment_transfer.recipient.address.country_subdivision", "MO");
        $map->set("payment_transfer.recipient.address.postal_code", "63368");
        $map->set("payment_transfer.recipient.address.country", "USA");
        $map->set("payment_transfer.recipient.phone", "11234567890");
        $map->set("payment_transfer.recipient.email", " Jane.Smith123@abcmail.com ");
        $map->set("payment_transfer.reconciliation_data.custom_field[0].name", " ABC");
        $map->set("payment_transfer.reconciliation_data.custom_field[0].value", " 123 ");
        $map->set("payment_transfer.reconciliation_data.custom_field[1].name", " DEF");
        $map->set("payment_transfer.reconciliation_data.custom_field[1].value", " 456 ");
        
        
    }
    
    
    public function TestSetAll()
    {
        $map = array( "Account" => array( "Status" => true, "Listed" => true, "ReasonCode" => "S", "Reason" => "STOLEN"));
        $baseMap = new RequestMap();
        $baseMap->setAll($map);
        
        $this->assertTrue($baseMap != NULL);
        $this->assertTrue($baseMap->containsKey("Account.Status"));
        $this->assertTrue($baseMap->size() == 1);
        $this->assertEquals("STOLEN", $baseMap->get("Account.Reason"));
        
        
    }
    

    

            
}