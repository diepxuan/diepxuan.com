<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Framework\View\Test\Unit\Page\Config\Generator;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Layout\Generator\Context;
use Magento\Framework\View\Layout\Reader\Context as ReaderContext;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Framework\View\Page\Config\Generator\Head;
use Magento\Framework\View\Page\Config\Structure;
use Magento\Framework\View\Page\Title;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test for page config generator model
 */
class HeadTest extends TestCase
{
    /**
     * @var Head
     */
    protected $headGenerator;

    /**
     * @var PageConfig|MockObject
     */
    protected $pageConfigMock;

    /**
     * @var UrlInterface|MockObject
     */
    protected $urlMock;

    /**
     * @var Title|MockObject
     */
    protected $title;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->pageConfigMock = $this->getMockBuilder(PageConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->title = $this->getMockBuilder(Title::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->urlMock = $this->getMockBuilder(UrlInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->headGenerator = $objectManagerHelper->getObject(
            Head::class,
            [
                'pageConfig' => $this->pageConfigMock,
                'url' => $this->urlMock,
            ]
        );
    }

    /**
     * @return void
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testProcess(): void
    {
        $generatorContextMock = $this->createMock(Context::class);
        $this->title->expects($this->any())->method('set')->with()->willReturnSelf();
        $structureMock = $this->createMock(Structure::class);
        $readerContextMock = $this->createMock(ReaderContext::class);
        $readerContextMock->expects($this->any())->method('getPageConfigStructure')->willReturn($structureMock);

        $structureMock->expects($this->once())->method('processRemoveAssets');
        $structureMock->expects($this->once())->method('processRemoveElementAttributes');
        $this->urlMock->expects($this->once())
            ->method('getUrl')
            ->with('customcss/render/css')
            ->willReturn('http://magento.dev/customcss/render/css');

        $assets = [
            'remoteCss' => [
                'src' => 'file-url-css',
                'src_type' => 'url',
                'content_type' => 'css',
                'media' => 'all'
            ],
            'remoteCssOrderedLast' => [
                'src' => 'file-url-css-last',
                'src_type' => 'url',
                'content_type' => 'css',
                'media' => 'all',
                'order' => 30
            ],
            'remoteCssOrderedFirst' => [
                'src' => 'file-url-css-first',
                'src_type' => 'url',
                'content_type' => 'css',
                'media' => 'all',
                'order' => 10
            ],
            'remoteLink' => [
                'src' => 'file-url-link',
                'src_type' => 'url',
                'media' => 'all'
            ],
            'controllerCss' => [
                'src' => 'customcss/render/css',
                'src_type' => 'controller',
                'content_type' => 'css',
                'media' => 'all'
            ],
            'name' => [
                'src' => 'file-path',
                'ie_condition' => 'lt IE 7',
                'content_type' => 'css',
                'media' => 'print'
            ]
        ];

        $this->pageConfigMock
            ->method('addRemotePageAsset')
            ->willReturnCallback(
                function ($arg1, $arg2, $arg3) {
                    if ($arg1 == 'file-url-css' &&
                        $arg2 == 'css' &&
                        $arg3 == ['attributes' => ['media' => 'all']]) {
                        return null;
                    } elseif ($arg1 == 'file-url-css-last' &&
                        $arg2 == 'css' &&
                        $arg3 == ['attributes' => ['media' => 'all'], 'order' => 30]) {
                        return null;
                    } elseif ($arg1 == 'file-url-css-first' &&
                        $arg2 == 'css' &&
                        $arg3 == ['attributes' => ['media' => 'all'], 'order' => 10]) {
                        return null;
                    } elseif ($arg1 == 'file-url-link' &&
                        $arg2 == Head::VIRTUAL_CONTENT_TYPE_LINK &&
                        $arg3 == ['attributes' => ['media' => 'all']]) {
                        return null;
                    } elseif ($arg1 == 'http://magento.dev/customcss/render/css' &&
                        $arg2 == 'css' && $arg3 == ['attributes' => ['media' => 'all']]) {
                        return null;
                    }
                }
            );
        $this->pageConfigMock->expects($this->once())
            ->method('addPageAsset')
            ->with('name', ['attributes' => ['media' => 'print'], 'ie_condition' => 'lt IE 7']);
        $structureMock->expects($this->once())
            ->method('getAssets')
            ->willReturn($assets);

        $title = 'Page title';
        $structureMock->expects($this->atLeastOnce())
            ->method('getTitle')
            ->willReturn($title);
        $this->pageConfigMock->expects($this->any())->method('getTitle')->willReturn($this->title);

        $metadata = ['name1' => 'content1', 'name2' => 'content2'];
        $structureMock->expects($this->once())
            ->method('getMetadata')
            ->willReturn($metadata);
        $this->pageConfigMock->expects($this->exactly(2))
            ->method('setMetadata')
            ->willReturnCallback(
                function ($arg1, $arg2) {
                    if ($arg1 == 'name1' && $arg2 == 'content1') {
                        return null;
                    } elseif ($arg1 == 'name2' && $arg2 == 'content2') {
                        return null;
                    }
                }
            );

        $elementAttributes = [
            PageConfig::ELEMENT_TYPE_BODY => [
                'body_attr_1' => 'body_value_1',
                'body_attr_2' => 'body_value_2'
            ],
            PageConfig::ELEMENT_TYPE_HTML => [
                'html_attr_1' => 'html_attr_1'
            ],
        ];
        $structureMock->expects($this->once())
            ->method('getElementAttributes')
            ->willReturn($elementAttributes);
        $this->pageConfigMock->expects($this->exactly(3))
            ->method('setElementAttribute')
            ->willReturnCallback(
                function ($arg1, $arg2, $arg3) {
                    if ($arg1 == PageConfig::ELEMENT_TYPE_BODY &&
                        $arg2 == 'body_attr_1' && $arg3 == 'body_value_1') {
                        return null;
                    } elseif ($arg1 == PageConfig::ELEMENT_TYPE_BODY &&
                        $arg2 == 'body_attr_2' && $arg3 == 'body_value_2') {
                        return null;
                    } elseif ($arg1 == PageConfig::ELEMENT_TYPE_HTML &&
                        $arg2 == 'html_attr_1' && $arg3 == 'html_attr_1') {
                        return null;
                    }
                }
            );

        $result = $this->headGenerator->process($readerContextMock, $generatorContextMock);
        $this->assertEquals($this->headGenerator, $result);
    }
}
