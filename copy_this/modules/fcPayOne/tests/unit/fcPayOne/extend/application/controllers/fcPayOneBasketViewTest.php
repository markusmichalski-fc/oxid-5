<?php
/** 
 * PAYONE OXID Connector is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PAYONE OXID Connector is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with PAYONE OXID Connector.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.payone.de
 * @copyright (C) Payone GmbH
 * @version   OXID eShop CE
 */
 
class Unit_fcPayOne_Extend_Application_Controllers_fcPayOneBasketView extends OxidTestCase {
    
    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array()) {
        $reflection = new \ReflectionClass(get_class($object));
        $method     = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }    
    
    /**
     * Set protected/private attribute value
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $propertyName property that shall be set
     * @param array  $value value to be set
     *
     * @return mixed Method return.
     */
    public function invokeSetAttribute(&$object, $propertyName, $value) {
        $reflection = new \ReflectionClass(get_class($object));
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }    

    
    /**
     * Testing _fcpoIsPayPalExpressActive for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcpoIsPayPalExpressActive_Coverage() {
        $oTestObject = oxNew('fcPayOneBasketView');
        
        $oMockBasket = $this->getMock('oxBasket', array('fcpoIsPayPalExpressActive'));
        $oMockBasket->expects($this->any())->method('fcpoIsPayPalExpressActive')->will($this->returnValue(true));

        $oHelper = $this->getMock('fcpohelper', array('getFactoryObject'));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockBasket));

        
        $this->assertEquals(true, $oTestObject->_fcpoIsPayPalExpressActive());
    }
    
    
    
    /**
     * Testing fcpoGetPayPalExpressPic for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoGetPayPalExpressPic_Coverage() {
        $oTestObject = $this->getMock('fcPayOneBasketView', array('_fcpoIsPayPalExpressActive'));
        $oTestObject->expects($this->any())->method('_fcpoIsPayPalExpressActive')->will($this->returnValue(true));

        $oMockBasket = $this->getMock('oxBasket', array('fcpoGetPayPalExpressPic'));
        $oMockBasket->expects($this->any())->method('fcpoGetPayPalExpressPic')->will($this->returnValue('somePic.jpg'));
        
        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('somePic.jpg'));
        
        
        $oHelper = $this->getMock('fcpohelper', array('fcpoFileExists','getFactoryObject'));
        $oHelper->expects($this->any())->method('fcpoFileExists')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockBasket));
        
        
        $this->invokeSetAttribute($oTestObject, '_sPayPalExpressPic', null);
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $sResponse  = $this->invokeMethod($oTestObject, 'fcpoGetPayPalExpressPic');
        $sExpect    = 'http://www.certification.dev/modules/fcPayOne/out/img/somePic.jpg';
        
        $this->assertEquals($sExpect, $sResponse);
    }
    
    
    /**
     * Testing fcpoUsePayPalExpress for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoUsePayPalExpress_Error() {
        $oTestObject = $this->getMock('fcPayOneBasketView', array('_fcpoIsPayPalExpressActive'));

        $oMockUtils = $this->getMock('oxUtils', array('redirect'));
        $oMockUtils->expects($this->any())->method('redirect')->will($this->returnValue(false));

        
        $aMockOutput['status'] = 'ERROR';
        $oMockRequest = $this->getMock('fcporequest', array('load', 'sendRequestGenericPayment'));
        $oMockRequest->expects($this->any())->method('sendRequestGenericPayment')->will($this->returnValue($aMockOutput));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));

        $this->assertEquals(false, $this->invokeMethod($oTestObject, 'fcpoUsePayPalExpress'));
    }
    

    /**
     * Testing fcpoUsePayPalExpress for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoUsePayPalExpress_Redirect() {
        $oTestObject = $this->getMock('fcPayOneBasketView', array('_fcpoIsPayPalExpressActive'));

        $oMockUtils = $this->getMock('oxUtils', array('redirect'));
        $oMockUtils->expects($this->any())->method('redirect')->will($this->returnValue(false));

        $aMockOutput['status'] = 'REDIRECT';
        $oMockRequest = $this->getMock('fcporequest', array('load', 'sendRequestGenericPayment'));
        $oMockRequest->expects($this->any())->method('sendRequestGenericPayment')->will($this->returnValue($aMockOutput));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
      
        $this->assertEquals(false, $this->invokeMethod($oTestObject, 'fcpoUsePayPalExpress'));
    }
    
    
    /**
     * Lil' paypalexpresslogo database helper
     * 
     * @param void
     * @return void
     */
    protected function _fcpoPreparePaypalExpressLogos() {
        $this->_fcpoTruncateTable('fcpopayoneexpresslogos');
        $sQuery = "
            INSERT INTO `fcpopayoneexpresslogos` (`OXID`, `FCPO_ACTIVE`, `FCPO_LANGID`, `FCPO_LOGO`, `FCPO_DEFAULT`) VALUES
            (1, 1, 0, 'fc_andre_sw_02_250px.1.png', 1),
            (2, 1, 1, 'btn_xpressCheckout_en.gif', 0)
        ";
        
        oxDb::getDb()->Execute($sQuery);
    }

    
    /**
     * Truncates table
     * 
     * @param void
     * @return void
     */
    protected function _fcpoTruncateTable($sTableName) {
        $sQuery = "TRUNCATE TABLE `{$sTableName}` ";
        
        oxDb::getDb()->Execute($sQuery);
    }
    
}
