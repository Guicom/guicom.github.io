<?php

namespace Drupal\Tests\soc_sales_locations\Unit;

use Drupal;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\soc_sales_locations\Service\SalesLocationsManagerImportService;
use Drupal\Tests\UnitTestCase;


class splitTestTermeAreaTest extends UnitTestCase {

  private $importService;

  public function setUp(){
    parent::setUp();
    $container =  new ContainerBuilder();

    $em =  $this->getMockBuilder(Drupal\Core\Entity\EntityTypeManager::class)
      ->disableOriginalConstructor()
      ->getMock();
    $file = $this->getMockBuilder(Drupal\Core\File\FileSystemInterface::class)
      ->disableOriginalConstructor()
      ->getMock();
    $this->importService =  new SalesLocationsManagerImportService($em, $file);
    $container->register('soc_sales_locations.manager.import', $this->importService);
    Drupal::setContainer($container);
    
  }

  public function _testSingleItemArea(){
/*
    $file =  $this->getMockBuilder(Drupal\Core\File\FileSystem::class)
      ->disableOriginalConstructor()
      ->getMock();

    $file->expects($this->any())
      ->method('realpath')
      ->will($this->returnValue(__DIR__.'/../../fixtures/IncorrectFormatFileCSV.csv'));
    $this->assertEquals('hello',$this->check('hello'));
    $this->importService->validate($file);
*/
  }


  public function testHowManyTerm(){
    $node = $this->getMockBuilder(Drupal\node\Entity\Node::class)
      ->disableOriginalConstructor()
      ->getMock();
    $helper =  new Drupal\soc_sales_locations\Helpers\StoreLocationImportHelper($node);
    $this->assertEquals(2, $helper->getCountTermsForRow('hello|world'));
    $this->assertEquals(1, $helper->getCountTermsForRow('hello'));
    $this->assertEquals(1, $helper->getCountTermsForRow('hello|'));

  }
  public  function testGetAllNamesWithPipeCharacters(){
    $node = $this->getMockBuilder(Drupal\node\Entity\Node::class)
      ->disableOriginalConstructor()
      ->getMock();
    $helper =  new Drupal\soc_sales_locations\Helpers\StoreLocationImportHelper($node);
    $this->assertEquals(['hello', 'world'], $helper->getNameForRow('hello|world'));
    $this->assertEquals(['hello', 'world'], $helper->getNameForRow('hello|world|'));

  }
}

