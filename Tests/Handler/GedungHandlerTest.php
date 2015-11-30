<?php

namespace Ais\GedungBundle\Tests\Handler;

use Ais\GedungBundle\Handler\GedungHandler;
use Ais\GedungBundle\Model\GedungInterface;
use Ais\GedungBundle\Entity\Gedung;

class GedungHandlerTest extends \PHPUnit_Framework_TestCase
{
    const DOSEN_CLASS = 'Ais\GedungBundle\Tests\Handler\DummyGedung';

    /** @var GedungHandler */
    protected $gedungHandler;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $om;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }
        
        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $this->om->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::DOSEN_CLASS))
            ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::DOSEN_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::DOSEN_CLASS));
    }


    public function testGet()
    {
        $id = 1;
        $gedung = $this->getGedung();
        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($id))
            ->will($this->returnValue($gedung));

        $this->gedungHandler = $this->createGedungHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);

        $this->gedungHandler->get($id);
    }

    public function testAll()
    {
        $offset = 1;
        $limit = 2;

        $gedungs = $this->getGedungs(2);
        $this->repository->expects($this->once())->method('findBy')
            ->with(array(), null, $limit, $offset)
            ->will($this->returnValue($gedungs));

        $this->gedungHandler = $this->createGedungHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);

        $all = $this->gedungHandler->all($limit, $offset);

        $this->assertEquals($gedungs, $all);
    }

    public function testPost()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $gedung = $this->getGedung();
        $gedung->setTitle($title);
        $gedung->setBody($body);

        $form = $this->getMock('Ais\GedungBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($gedung));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->gedungHandler = $this->createGedungHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $gedungObject = $this->gedungHandler->post($parameters);

        $this->assertEquals($gedungObject, $gedung);
    }

    /**
     * @expectedException Ais\GedungBundle\Exception\InvalidFormException
     */
    public function testPostShouldRaiseException()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $gedung = $this->getGedung();
        $gedung->setTitle($title);
        $gedung->setBody($body);

        $form = $this->getMock('Ais\GedungBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->gedungHandler = $this->createGedungHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $this->gedungHandler->post($parameters);
    }

    public function testPut()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $gedung = $this->getGedung();
        $gedung->setTitle($title);
        $gedung->setBody($body);

        $form = $this->getMock('Ais\GedungBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($gedung));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->gedungHandler = $this->createGedungHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $gedungObject = $this->gedungHandler->put($gedung, $parameters);

        $this->assertEquals($gedungObject, $gedung);
    }

    public function testPatch()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('body' => $body);

        $gedung = $this->getGedung();
        $gedung->setTitle($title);
        $gedung->setBody($body);

        $form = $this->getMock('Ais\GedungBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($gedung));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->gedungHandler = $this->createGedungHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $gedungObject = $this->gedungHandler->patch($gedung, $parameters);

        $this->assertEquals($gedungObject, $gedung);
    }


    protected function createGedungHandler($objectManager, $gedungClass, $formFactory)
    {
        return new GedungHandler($objectManager, $gedungClass, $formFactory);
    }

    protected function getGedung()
    {
        $gedungClass = static::DOSEN_CLASS;

        return new $gedungClass();
    }

    protected function getGedungs($maxGedungs = 5)
    {
        $gedungs = array();
        for($i = 0; $i < $maxGedungs; $i++) {
            $gedungs[] = $this->getGedung();
        }

        return $gedungs;
    }
}

class DummyGedung extends Gedung
{
}
