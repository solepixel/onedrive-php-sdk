<?php

declare(strict_types=1);

namespace Test\Unit\Krizalys\Onedrive\Proxy;

use GuzzleHttp\Psr7\Stream;
use Krizalys\Onedrive\Constant\SharingLinkScope;
use Krizalys\Onedrive\Constant\SharingLinkType;
use Krizalys\Onedrive\Parameter\DriveItemParameterDirectorInterface;
use Krizalys\Onedrive\Proxy\AudioProxy;
use Krizalys\Onedrive\Proxy\DeletedProxy;
use Krizalys\Onedrive\Proxy\DriveItemProxy;
use Krizalys\Onedrive\Proxy\DriveItemVersionProxy;
use Krizalys\Onedrive\Proxy\FileProxy;
use Krizalys\Onedrive\Proxy\FileSystemInfoProxy;
use Krizalys\Onedrive\Proxy\FolderProxy;
use Krizalys\Onedrive\Proxy\GeoCoordinatesProxy;
use Krizalys\Onedrive\Proxy\ImageProxy;
use Krizalys\Onedrive\Proxy\ListItemProxy;
use Krizalys\Onedrive\Proxy\PackageProxy;
use Krizalys\Onedrive\Proxy\PermissionProxy;
use Krizalys\Onedrive\Proxy\PhotoProxy;
use Krizalys\Onedrive\Proxy\PublicationFacetProxy;
use Krizalys\Onedrive\Proxy\RemoteItemProxy;
use Krizalys\Onedrive\Proxy\RootProxy;
use Krizalys\Onedrive\Proxy\SearchResultProxy;
use Krizalys\Onedrive\Proxy\SharedProxy;
use Krizalys\Onedrive\Proxy\SharepointIdsProxy;
use Krizalys\Onedrive\Proxy\SharingLinkProxy;
use Krizalys\Onedrive\Proxy\SpecialFolderProxy;
use Krizalys\Onedrive\Proxy\ThumbnailProxy;
use Krizalys\Onedrive\Proxy\UploadSessionProxy;
use Krizalys\Onedrive\Proxy\VideoProxy;
use Krizalys\Onedrive\Proxy\WorkbookProxy;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Http\GraphRequest;
use Microsoft\Graph\Http\GraphResponse;
use Microsoft\Graph\Model\Audio;
use Microsoft\Graph\Model\Deleted;
use Microsoft\Graph\Model\DriveItem;
use Microsoft\Graph\Model\DriveItemVersion;
use Microsoft\Graph\Model\File;
use Microsoft\Graph\Model\FileSystemInfo;
use Microsoft\Graph\Model\Folder;
use Microsoft\Graph\Model\GeoCoordinates;
use Microsoft\Graph\Model\Image;
use Microsoft\Graph\Model\ItemReference;
use Microsoft\Graph\Model\ListItem;
use Microsoft\Graph\Model\Package;
use Microsoft\Graph\Model\Permission;
use Microsoft\Graph\Model\Photo;
use Microsoft\Graph\Model\PublicationFacet;
use Microsoft\Graph\Model\RemoteItem;
use Microsoft\Graph\Model\Root;
use Microsoft\Graph\Model\SearchResult;
use Microsoft\Graph\Model\Shared;
use Microsoft\Graph\Model\SharepointIds;
use Microsoft\Graph\Model\SharingLink;
use Microsoft\Graph\Model\SpecialFolder;
use Microsoft\Graph\Model\Thumbnail;
use Microsoft\Graph\Model\UploadSession;
use Microsoft\Graph\Model\Video;
use Microsoft\Graph\Model\Workbook;
use PHPUnit\Framework\TestCase;

class DriveItemProxyTest extends TestCase
{
    const DRIVE_ITEM_ID     = '0123';
    const UPLOAD_SESSION_ID = '4567';

    public function testAudioShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $audio = $this->createMock(Audio::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getAudio')->willReturn($audio);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(AudioProxy::class, $sut->audio);
    }

    public function testContentShouldReturnExpectedValue()
    {
        $this->testDownloadShouldReturnExpectedValue();
    }

    public function testCTagShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getCTag')->willReturn('1234');

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertIsString($sut->cTag);
        $this->assertSame('1234', $sut->cTag);
    }

    public function testDeletedShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $deleted = $this->createMock(Deleted::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getDeleted')->willReturn($deleted);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(DeletedProxy::class, $sut->deleted);
    }

    public function testFileShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $file = $this->createMock(File::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getFile')->willReturn($file);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(FileProxy::class, $sut->file);
    }

    public function testFileSystemInfoShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $fileSystemInfo = $this->createMock(FileSystemInfo::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getFileSystemInfo')->willReturn($fileSystemInfo);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(FileSystemInfoProxy::class, $sut->fileSystemInfo);
    }

    public function testFolderShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $folder = $this->createMock(Folder::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getFolder')->willReturn($folder);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(FolderProxy::class, $sut->folder);
    }

    public function testImageShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $image = $this->createMock(Image::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getImage')->willReturn($image);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(ImageProxy::class, $sut->image);
    }

    public function testLocationShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $geoCoordinates = $this->createMock(GeoCoordinates::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getLocation')->willReturn($geoCoordinates);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(GeoCoordinatesProxy::class, $sut->location);
    }

    public function testPackageShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $package = $this->createMock(Package::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getPackage')->willReturn($package);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(PackageProxy::class, $sut->package);
    }

    public function testPhotoShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $photo = $this->createMock(Photo::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getPhoto')->willReturn($photo);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(PhotoProxy::class, $sut->photo);
    }

    public function testPublicationShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $publicationFacet = $this->createMock(PublicationFacet::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getPublication')->willReturn($publicationFacet);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(PublicationFacetProxy::class, $sut->publication);
    }

    public function testRemoteItemShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $remoteItem = $this->createMock(RemoteItem::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getRemoteItem')->willReturn($remoteItem);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(RemoteItemProxy::class, $sut->remoteItem);
    }

    public function testRootShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $root = $this->createMock(Root::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getRoot')->willReturn($root);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(RootProxy::class, $sut->root);
    }

    public function testSearchResultShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $searchResult = $this->createMock(SearchResult::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getSearchResult')->willReturn($searchResult);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(SearchResultProxy::class, $sut->searchResult);
    }

    public function testSharedShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $shared = $this->createMock(Shared::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getShared')->willReturn($shared);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(SharedProxy::class, $sut->shared);
    }

    public function testSharepointIdsShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $sharepointIds = $this->createMock(SharepointIds::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getSharepointIds')->willReturn($sharepointIds);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(SharepointIdsProxy::class, $sut->sharepointIds);
    }

    public function testSizeShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getSize')->willReturn(1234);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertIsInt($sut->size);
        $this->assertSame(1234, $sut->size);
    }

    public function testSpecialFolderShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $specialFolder = $this->createMock(SpecialFolder::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getSpecialFolder')->willReturn($specialFolder);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(SpecialFolderProxy::class, $sut->specialFolder);
    }

    public function testVideoShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $video = $this->createMock(Video::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getVideo')->willReturn($video);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(VideoProxy::class, $sut->video);
    }

    public function testWebDavUrlShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getWebDavUrl')->willReturn('http://webd.av/url');

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertIsString($sut->webDavUrl);
        $this->assertSame('http://webd.av/url', $sut->webDavUrl);
    }

    public function testChildrenShouldReturnExpectedValue()
    {
        $this->testGetChildrenShouldReturnExpectedValue();
    }

    public function testListItemShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $listItem = $this->createMock(ListItem::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getListItem')->willReturn($listItem);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(ListItemProxy::class, $sut->listItem);
    }

    public function testPermissionsShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $permissions = [
            $this->createMock(Permission::class),
            $this->createMock(Permission::class),
        ];

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getPermissions')->willReturn($permissions);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut    = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $actual = $sut->permissions;
        $this->assertIsArray($actual);
        $this->assertCount(2, $actual);

        foreach ($actual as $permission) {
            $this->assertInstanceOf(PermissionProxy::class, $permission);
        }
    }

    public function testThumbnailsShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $thumbnails = [
            $this->createMock(Thumbnail::class),
            $this->createMock(Thumbnail::class),
        ];

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getThumbnails')->willReturn($thumbnails);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut    = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $actual = $sut->thumbnails;
        $this->assertIsArray($actual);
        $this->assertCount(2, $actual);

        foreach ($actual as $thumbnail) {
            $this->assertInstanceOf(ThumbnailProxy::class, $thumbnail);
        }
    }

    public function testVersionsShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $driveItemVersions = [
            $this->createMock(DriveItemVersion::class),
            $this->createMock(DriveItemVersion::class),
        ];

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getVersions')->willReturn($driveItemVersions);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut    = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $actual = $sut->versions;
        $this->assertIsArray($actual);
        $this->assertCount(2, $actual);

        foreach ($actual as $driveItemVersion) {
            $this->assertInstanceOf(DriveItemVersionProxy::class, $driveItemVersion);
        }
    }

    public function testWorkbookShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $workbook = $this->createMock(Workbook::class);

        $driveItem = $this->createMock(DriveItem::class);
        $driveItem->method('getWorkbook')->willReturn($workbook);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new DriveItemProxy($graph, $driveItem, $parameterDirector);
        $this->assertInstanceOf(WorkbookProxy::class, $sut->workbook);
    }

    public function testCreateFolderShouldReturnExpectedValue()
    {
        $item = $this->mockDriveItem();

        $childItem = $this->mockDriveItem(['id' => self::DRIVE_ITEM_ID]);

        $response = $this->mockResponse(201, ['body' => $childItem]);

        $graph = $this->mockGraph($response);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);
        $parameterDirector->method('buildPostChildrenBodyParameters')->willReturn([]);

        $sut    = new DriveItemProxy($graph, $item, $parameterDirector);
        $actual = $sut->createFolder('Irrelevant', []);
        $this->assertInstanceOf(DriveItemProxy::class, $actual);
        $this->assertSame(self::DRIVE_ITEM_ID, $actual->id);
    }

    public function testGetChildrenShouldReturnExpectedValue()
    {
        $childItems = [
            $this->mockDriveItem(['id' => '0001']),
            $this->mockDriveItem(['id' => '0002']),
        ];

        $item = $this->mockDriveItem();

        $graph = $this->mockGraphWithCollectionResponse($childItems);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut    = new DriveItemProxy($graph, $item, $parameterDirector);
        $actual = $sut->getChildren();
        $this->assertIsArray($actual);
        $this->assertCount(2, $actual);

        foreach ($actual as $child) {
            $this->assertInstanceOf(DriveItemProxy::class, $child);
        }

        $this->assertSame('0001', $actual[0]->id);
        $this->assertSame('0002', $actual[1]->id);
    }

    public function testUploadShouldReturnExpectedValue()
    {
        $item = $this->mockDriveItem();

        $childItem = $this->mockDriveItem(['id' => self::DRIVE_ITEM_ID]);

        $response = $this->mockResponse(201, ['body' => $childItem]);

        $graph = $this->mockGraph($response);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut    = new DriveItemProxy($graph, $item, $parameterDirector);
        $actual = $sut->upload('Irrelevant', 'Test content', []);
        $this->assertInstanceOf(DriveItemProxy::class, $actual);
        $this->assertSame(self::DRIVE_ITEM_ID, $actual->id);
    }

    public function testStartUploadShouldReturnExpectedValue()
    {
        $item = $this->mockDriveItem();

        $uploadSession = $this->mockUploadSession(['id' => self::UPLOAD_SESSION_ID]);

        $response = $this->mockResponse(200, ['body' => $uploadSession]);

        $graph = $this->mockGraph($response);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);
        $parameterDirector->method('buildPostChildrenBodyParameters')->willReturn([]);

        $sut    = new DriveItemProxy($graph, $item, $parameterDirector);
        $actual = $sut->startUpload('Irrelevant', 'Test content', []);
        $this->assertInstanceOf(UploadSessionProxy::class, $actual);
        $this->assertSame(self::UPLOAD_SESSION_ID, $actual->id);
    }

    public function testDownloadShouldReturnExpectedValue()
    {
        $item = $this->mockDriveItem();

        $stream = $this->mockStream();

        $graph = $this->mockGraph($stream);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut    = new DriveItemProxy($graph, $item, $parameterDirector);
        $actual = $sut->download();
        $this->assertSame($stream, $actual);
    }

    public function testRenameShouldReturnExpectedValue()
    {
        $item = $this->mockDriveItem();

        $renamedItem = $this->mockDriveItem(['id' => self::DRIVE_ITEM_ID]);

        $response = $this->mockResponse(200, ['body' => $renamedItem]);

        $graph = $this->mockGraph($response);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut    = new DriveItemProxy($graph, $item, $parameterDirector);
        $actual = $sut->rename('Irrelevant', []);
        $this->assertInstanceOf(DriveItemProxy::class, $actual);
        $this->assertSame($actual->id, self::DRIVE_ITEM_ID);
    }

    public function testMoveShouldReturnExpectedValue()
    {
        $item = $this->mockDriveItem();

        $movedItem = $this->mockDriveItem(['id' => self::DRIVE_ITEM_ID]);

        $destinationItem = $this->mockDriveItemProxy();

        $response = $this->mockResponse(200, ['body' => $movedItem]);

        $graph = $this->mockGraph($response);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut    = new DriveItemProxy($graph, $item, $parameterDirector);
        $actual = $sut->move($destinationItem, []);
        $this->assertInstanceOf(DriveItemProxy::class, $actual);
        $this->assertSame($actual->id, self::DRIVE_ITEM_ID);
    }

    public function testCopyShouldReturnExpectedValue()
    {
        $item = $this->mockDriveItem();

        $destinationItem = $this->mockDriveItemProxy();

        $response = $this->mockResponse(202, ['headers' => ['Location' => ['http://progre.ss/url']]]);

        $graph = $this->mockGraph($response);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut    = new DriveItemProxy($graph, $item, $parameterDirector);
        $actual = $sut->copy($destinationItem, []);
        $this->assertIsString($actual);
        $this->assertSame('http://progre.ss/url', $actual);
    }

    public function testCreateLinkShouldReturnExpectedValue()
    {
        $item = $this->mockDriveItem();

        $sharingLink = $this->createMock(SharingLink::class);
        $sharingLink->method('getScope')->willReturn(SharingLinkScope::ANONYMOUS);

        $permission = $this->createMock(Permission::class);
        $permission->method('getLink')->willReturn($sharingLink);

        $response = $this->mockResponse(201, ['body' => $permission]);

        $graph = $this->mockGraph($response);

        $parameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut    = new DriveItemProxy($graph, $item, $parameterDirector);
        $actual = $sut->createLink(SharingLinkType::VIEW, []);
        $this->assertInstanceOf(PermissionProxy::class, $actual);
        $this->assertInstanceOf(SharingLinkProxy::class, $actual->link);
        $this->assertInternalType('string', $actual->link->scope);
        $this->assertSame(SharingLinkScope::ANONYMOUS, $actual->link->scope);
    }

    private function mockStream()
    {
        $stream = $this->createMock(Stream::class);

        return $stream;
    }

    private function mockResponse($status, array $options = [])
    {
        $response = $this->createMock(GraphResponse::class);
        $response->method('getStatus')->willReturn((string) $status);

        if (array_key_exists('headers', $options)) {
            $response->method('getHeaders')->willReturn($options['headers']);
        }

        if (array_key_exists('body', $options)) {
            $response->method('getResponseAsObject')->willReturn($options['body']);
        }

        return $response;
    }

    private function mockGraph($response)
    {
        $request = $this->createMock(GraphRequest::class);
        $request->method('addHeaders')->willReturnSelf();
        $request->method('attachBody')->willReturnSelf();
        $request->method('setReturnType')->willReturnSelf();
        $request->method('execute')->willReturn($response);
        $graph = $this->createMock(Graph::class);
        $graph->method('createRequest')->willReturn($request);

        return $graph;
    }

    private function mockGraphWithCollectionResponse(array $body)
    {
        $response = $this->createMock(GraphResponse::class);
        $response->method('getStatus')->willReturn('200');
        $response->method('getResponseAsObject')->willReturn($body);
        $request = $this->createMock(GraphRequest::class);
        $request->method('execute')->willReturn($response);
        $graph = $this->createMock(Graph::class);
        $graph->method('createCollectionRequest')->willReturn($request);

        return $graph;
    }

    private function mockDriveItem(array $options = [])
    {
        $itemReference = $this->createMock(ItemReference::class);
        $itemReference->method('getDriveId')->willReturn('');
        $item = $this->createMock(DriveItem::class);
        $item->method('getParentReference')->willReturn($itemReference);

        if (array_key_exists('id', $options)) {
            $item->method('getId')->willReturn($options['id']);
        }

        return $item;
    }

    private function mockDriveItemProxy()
    {
        $driveItemProxy = $this->createMock(DriveItemProxy::class);

        return $driveItemProxy;
    }

    private function mockUploadSession(array $options = [])
    {
        $uploadSession = $this->createMock(UploadSession::class);

        if (array_key_exists('id', $options)) {
            $uploadSession->method('getId')->willReturn($options['id']);
        }

        return $uploadSession;
    }
}
