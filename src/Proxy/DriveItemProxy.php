<?php

/**
 * This file is part of Krizalys' OneDrive SDK for PHP.
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 *
 * @author    Christophe Vidal
 * @copyright 2008-2019 Christophe Vidal (http://www.krizalys.com)
 * @license   https://opensource.org/licenses/BSD-3-Clause 3-Clause BSD License
 * @link      https://github.com/krizalys/onedrive-php-sdk
 */

namespace Krizalys\Onedrive\Proxy;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Stream;
use Krizalys\Onedrive\Exception\ConflictException;
use Krizalys\Onedrive\Parameter\DriveItemParameterDirectorInterface;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model\DriveItem;
use Microsoft\Graph\Model\DriveItemVersion;
use Microsoft\Graph\Model\Permission;
use Microsoft\Graph\Model\Thumbnail;
use Microsoft\Graph\Model\UploadSession;

/**
 * A proxy to a \Microsoft\Graph\Model\DriveItem instance.
 *
 * @property-read \Krizalys\Onedrive\Proxy\AudioProxy $audio
 *                The audio.
 * @property-read \GuzzleHttp\Psr7\Stream $content
 *                The content.
 * @property-read string $cTag
 *                The CTag.
 * @property-read \Krizalys\Onedrive\Proxy\DeletedProxy $deleted
 *                The deleted.
 * @property-read \Krizalys\Onedrive\Proxy\FileProxy $file
 *                The file.
 * @property-read \Krizalys\Onedrive\Proxy\FileSystemInfoProxy $fileSystemInfo
 *                The file system info.
 * @property-read \Krizalys\Onedrive\Proxy\FolderProxy $folder
 *                The folder.
 * @property-read \Krizalys\Onedrive\Proxy\ImageProxy $image
 *                The image.
 * @property-read \Krizalys\Onedrive\Proxy\GeoCoordinatesProxy $location
 *                The location.
 * @property-read \Krizalys\Onedrive\Proxy\PackageProxy $package
 *                The package.
 * @property-read \Krizalys\Onedrive\Proxy\PhotoProxy $photo
 *                The photo.
 * @property-read \Krizalys\Onedrive\Proxy\PublicationFacetProxy $publication
 *                The publication.
 * @property-read \Krizalys\Onedrive\Proxy\RemoteItemProxy $remoteItem
 *                The remote item.
 * @property-read \Krizalys\Onedrive\Proxy\RootProxy $root
 *                The root.
 * @property-read \Krizalys\Onedrive\Proxy\SearchResultProxy $searchResult
 *                The search result.
 * @property-read \Krizalys\Onedrive\Proxy\SharedProxy $shared
 *                The shared.
 * @property-read \Krizalys\Onedrive\Proxy\SharepointIdsProxy $sharepointIds
 *                The SharePoint IDs.
 * @property-read int $size
 *                The size.
 * @property-read \Krizalys\Onedrive\Proxy\SpecialFolderProxy $specialFolder
 *                The special folder.
 * @property-read \Krizalys\Onedrive\Proxy\VideoProxy $video
 *                The video.
 * @property-read string $webDavUrl
 *                The WebDAV URL.
 * @property-read \Krizalys\Onedrive\Proxy\DriveItem[] $children
 *                The children.
 * @property-read \Krizalys\Onedrive\Proxy\ListItemProxy $listItem
 *                The list item.
 * @property-read \Krizalys\Onedrive\Proxy\PermissionProxy[] $permissions
 *                The permissions.
 * @property-read \Krizalys\Onedrive\Proxy\ThumbnailProxy[] $thumbnails
 *                The thumbnails.
 * @property-read \Krizalys\Onedrive\Proxy\DriveItemVersionProxy[] $versions
 *                The versions.
 * @property-read \Krizalys\Onedrive\Proxy\WorkbookProxy $workbook
 *                The workbook.
 *
 * @since 2.0.0
 *
 * @api
 *
 * @link https://github.com/microsoftgraph/msgraph-sdk-php/blob/dev/src/Model/DriveItem.php
 */
class DriveItemProxy extends BaseItemProxy
{
    /**
     * @var DriveItemParameterDirectorInterface
     *      The drive item parameter director.
     */
    private $parameterDirector;

    /**
     * Constructor.
     *
     * @param Graph $graph
     *        The Microsoft Graph.
     * @param DriveItem $driveItem
     *        The drive item.
     * @param DriveItemParameterDirectorInterface $parameterDirector
     *        The drive item parameter director.
     *
     * @since 2.0.0
     */
    public function __construct(
        Graph $graph,
        DriveItem $driveItem,
        DriveItemParameterDirectorInterface $parameterDirector
    ) {
        parent::__construct($graph, $driveItem);
        $this->parameterDirector = $parameterDirector;
    }

    /**
     * Getter.
     *
     * @param string $name
     *        The name.
     *
     * @return mixed
     *         The value.
     *
     * @since 2.0.0
     */
    public function __get($name)
    {
        $driveItem = $this->entity;

        switch ($name) {
            case 'audio':
                $audio = $driveItem->getAudio();
                return $audio !== null ? new AudioProxy($this->graph, $audio) : null;

            case 'content':
                return $this->download();

            case 'cTag':
                return $driveItem->getCTag();

            case 'deleted':
                $deleted = $driveItem->getDeleted();
                return $deleted !== null ? new DeletedProxy($this->graph, $deleted) : null;

            case 'file':
                $file = $driveItem->getFile();
                return $file !== null ? new FileProxy($this->graph, $file) : null;

            case 'fileSystemInfo':
                $fileSystemInfo = $driveItem->getFileSystemInfo();
                return $fileSystemInfo !== null ? new FileSystemInfoProxy($this->graph, $fileSystemInfo) : null;

            case 'folder':
                $folder = $driveItem->getFolder();
                return $folder !== null ? new FolderProxy($this->graph, $folder) : null;

            case 'image':
                $image = $driveItem->getImage();
                return $image !== null ? new ImageProxy($this->graph, $image) : null;

            case 'location':
                $location = $driveItem->getLocation();
                return $location !== null ? new GeoCoordinatesProxy($this->graph, $location) : null;

            case 'package':
                $package = $driveItem->getPackage();
                return $package !== null ? new PackageProxy($this->graph, $package) : null;

            case 'photo':
                $photo = $driveItem->getPhoto();
                return $photo !== null ? new PhotoProxy($this->graph, $photo) : null;

            case 'publication':
                $publication = $driveItem->getPublication();
                return $publication !== null ? new PublicationFacetProxy($this->graph, $publication) : null;

            case 'remoteItem':
                $remoteItem = $driveItem->getRemoteItem();
                return $remoteItem !== null ? new RemoteItemProxy($this->graph, $remoteItem) : null;

            case 'root':
                $root = $driveItem->getRoot();
                return $root !== null ? new RootProxy($this->graph, $root) : null;

            case 'searchResult':
                $searchResult = $driveItem->getSearchResult();
                return $searchResult !== null ? new SearchResultProxy($this->graph, $searchResult) : null;

            case 'shared':
                $shared = $driveItem->getShared();
                return $shared !== null ? new SharedProxy($this->graph, $shared) : null;

            case 'sharepointIds':
                $sharepointIds = $driveItem->getSharepointIds();
                return $sharepointIds !== null ? new SharepointIdsProxy($this->graph, $sharepointIds) : null;

            case 'size':
                return $driveItem->getSize();

            case 'specialFolder':
                $specialFolder = $driveItem->getSpecialFolder();
                return $specialFolder !== null ? new SpecialFolderProxy($this->graph, $specialFolder) : null;

            case 'video':
                $video = $driveItem->getVideo();
                return $video !== null ? new VideoProxy($this->graph, $video) : null;

            case 'webDavUrl':
                return $driveItem->getWebDavUrl();

            case 'children':
                return $this->getChildren();

            case 'listItem':
                $listItem = $driveItem->getListItem();
                return $listItem !== null ? new ListItemProxy($this->graph, $listItem) : null;

            case 'permissions':
                $permissions = $driveItem->getPermissions();

                return $permissions !== null ? array_map(function (Permission $permission) {
                    return new PermissionProxy($this->graph, $permission);
                }, $permissions) : null;

            case 'thumbnails':
                $thumbnails = $driveItem->getThumbnails();

                return $thumbnails !== null ? array_map(function (Thumbnail $thumbnail) {
                    return new ThumbnailProxy($this->graph, $thumbnail);
                }, $thumbnails) : null;

            case 'versions':
                $versions = $driveItem->getVersions();

                return $versions !== null ? array_map(function (DriveItemVersion $driveItemVersion) {
                    return new DriveItemVersionProxy($this->graph, $driveItemVersion);
                }, $versions) : null;

            case 'workbook':
                $workbook = $driveItem->getWorkbook();
                return $workbook !== null ? new WorkbookProxy($this->graph, $workbook) : null;

            default:
                return parent::__get($name);
        }
    }

    /**
     * Creates a folder under this folder drive item.
     *
     * This operation is supported only on folders (as opposed to files): it
     * fails if this `DriveItemProxy` instance does not refer to a folder.
     *
     * The conflict behavior may be given as an option, for example:
     *
     * ```php
     * $driveItem->createFolder('folder');
     * // => Folder 'folder' created under $driveItem.
     *
     * $childDriveItem1 = $driveItem->createFolder(
     *     'folder',
     *     ['conflictBehavior' => ConflictBehavior::RENAME]
     * );
     * // => Folder 'folder 1' created under $driveItem.
     * ```
     *
     * @param string $name
     *        The name.
     * @param mixed[string] $options
     *        The options. Supported options:
     *          - `'description'` *(string)*: the description of the folder
     *            created ;
     *          - `'conflictBehavior'` *(string)*: the conflict behavior.
     *
     * @return DriveItemProxy
     *         The drive item created.
     *
     * @since 2.0.0
     *
     * @api
     *
     * @link https://docs.microsoft.com/en-us/onedrive/developer/rest-api/api/driveitem_post_children?view=odsp-graph-online
     *       Create a new folder in a drive
     */
    public function createFolder($name, array $options = [])
    {
        $driveLocator = "/drives/{$this->parentReference->driveId}";
        $itemLocator  = "/items/{$this->id}";
        $endpoint     = "$driveLocator$itemLocator/children";

        $bodyParams = $this
            ->parameterDirector
            ->buildPostChildrenBodyParameters($options);

        $body = array_replace_recursive([
            'folder' => [
                '@odata.type' => 'microsoft.graph.folder',
            ],
            'name' => (string) $name,
        ], $bodyParams);

        try {
            $response = $this
                ->graph
                ->createRequest('POST', $endpoint)
                ->attachBody($body)
                ->execute();

            $status = $response->getStatus();

            if ($status != 200 && $status != 201) {
                throw new \Exception("Unexpected status code produced by 'POST $endpoint': $status");
            }

            $driveItem = $response->getResponseAsObject(DriveItem::class);

            return new self($this->graph, $driveItem, $this->parameterDirector);
        } catch (ClientException $exception) {
            $status = $exception
                ->getResponse()
                ->getStatusCode();

            if ($status == 409) {
                $message = sprintf(
                    'There is already a drive item named "%s" in this folder',
                    $name
                );

                throw new ConflictException($message);
            }

            throw $exception;
        }
    }

    /**
     * Gets this folder drive item's children.
     *
     * This operation is supported only on folders (as opposed to files): it
     * fails if this `DriveItemProxy` instance does not refer to a folder.
     *
     * The retrieved result set may be refined via the `$options` parameter. For
     * example, to retrieve the first ten children sorted by name in descending
     * order:
     *
     * ```php
     * $childDriveItems = $driveItem->getChildren([
     *     'top' => 10,
     *     'orderBy' => [
     *         ['name', 'desc'],
     *     ],
     * ]);
     * ```
     *
     * @param mixed[string] $options
     *        The options. Supported options:
     *          - `'top'` *(number)*: The number of items to retrieve. Default:
     *            `200` ;
     *          - `'orderBy'` *(string[][])*: The list of property-direction
     *            tuples to use to sort retrieved items. Each tuple consists of
     *            a property and a direction, separated from each other by
     *            whitespace.
     *            Although the official OneDrive documentation claims to support
     *            sorting by multiple properties, we did not acknowledge this
     *            behavior, yet, and instead got Bad Request errors from the
     *            OneDrive API. We do not recommend relying on this feature.
     *            This option anticipates a related change in the OneDrive API
     *            and is designed to support this feature without introducing
     *            breaking changes.
     *            Supported properties:
     *              - `'name'`: sorts by name ;
     *              - `'size'`: sorts by size ;
     *              - `'lastModifiedDateTime'`: sorts by last modified date;
     *              - `'url'`: sorts by URL.
     *            Some properties are supported only on some versions of
     *            OneDrive. See
     *            {@link https://docs.microsoft.com/en-us/onedrive/developer/rest-api/concepts/optional-query-parameters?view=odsp-graph-online#sorting-collections}
     *            for the reference.
     *            The direction defaults to ascending if not given. Supported
     *            directions:
     *              - `'asc'`: sorts by the given property in ascending in
     *                order ;
     *              - `'desc'`: sorts by the given property in descending in
     *                order.
     *
     * @return DriveItemProxy[]
     *         The child drive items.
     *
     * @since 2.0.0
     *
     * @api
     *
     * @link https://docs.microsoft.com/en-us/onedrive/developer/rest-api/api/driveitem_list_children?view=odsp-graph-online
     *       List children of a driveItem
     *
     * @todo Support pagination using a native iterator.
     * @todo Support more query string parameters.
     */
    public function getChildren(array $options = [])
    {
        $driveLocator = "/drives/{$this->parentReference->driveId}";
        $itemLocator  = "/items/{$this->id}";
        $endpoint     = "$driveLocator$itemLocator/children";

        $queryParams = $this
            ->parameterDirector
            ->buildGetChildren($options);

        if (!empty($queryParams)) {
            $queryString = http_build_query($queryParams, '', '&', PHP_QUERY_RFC3986);
            $endpoint    = "$endpoint?$queryString";
        }

        $response = $this
            ->graph
            ->createCollectionRequest('GET', $endpoint)
            ->execute();

        $status = $response->getStatus();

        if ($status != 200) {
            throw new \Exception("Unexpected status code produced by 'GET $endpoint': $status");
        }

        $driveItems = $response->getResponseAsObject(DriveItem::class);

        if (!is_array($driveItems)) {
            return [];
        }

        return array_map(function (DriveItem $driveItem) {
            return new self($this->graph, $driveItem, $this->parameterDirector);
        }, $driveItems);
    }

    /**
     * Deletes this drive item.
     *
     * @since 2.0.0
     *
     * @api
     *
     * @link https://docs.microsoft.com/en-us/onedrive/developer/rest-api/api/driveitem_delete?view=odsp-graph-online
     *       Delete a DriveItem
     */
    public function delete()
    {
        $driveLocator = "/drives/{$this->parentReference->driveId}";
        $itemLocator  = "/items/{$this->id}";
        $endpoint     = "$driveLocator$itemLocator";

        $response = $this
            ->graph
            ->createRequest('DELETE', $endpoint)
            ->execute();

        $status = $response->getStatus();

        if ($status != 204) {
            throw new \Exception("Unexpected status code produced by 'DELETE $endpoint': $status");
        }
    }

    /**
     * Uploads a file under this folder drive item.
     *
     * This operation is supported only on folders (as opposed to files): it
     * fails if this `DriveItemProxy` instance does not refer to a folder.
     *
     * The conflict behavior and the MIME type of the drive item may be given as
     * options, for example:
     *
     * ```php
     * $driveItem->upload(
     *     'file.txt',
     *     'Some content',
     *     ['contentType' => 'text/plain']
     * );
     * // => Text file 'file.txt' created under $driveItem.
     *
     * $childDriveItem1 = $driveItem->upload(
     *     'file.txt',
     *     'Some other content',
     *     [
     *         'conflictBehavior' => ConflictBehavior::RENAME,
     *         'contentType'      => 'text/plain',
     *     ]
     * );
     * // => Text file 'file 1.txt' created under $driveItem.
     * ```
     *
     * @param string $name
     *        The name.
     * @param string|resource|\GuzzleHttp\Psr7\Stream $content
     *        The content.
     * @param mixed[string] $options
     *        The options. Supported options:
     *          - `'conflictBehavior'` *(string)*: the conflict behavior ;
     *          - `'contentType'` *(string)*: the MIME type of the uploaded
     *            file.
     *
     * @return DriveItemProxy
     *         The drive item created.
     *
     * @since 2.0.0
     *
     * @api
     *
     * @link https://docs.microsoft.com/en-us/onedrive/developer/rest-api/api/driveitem_put_content?view=odsp-graph-online
     *       Upload or replace the contents of a DriveItem
     *
     * @todo Support content type in options.
     */
    public function upload($name, $content, array $options = [])
    {
        if (array_key_exists('Content-Type', $options)) {
            $message = 'The \'Content-Type\' option is deprecated and will'
                . ' be removed in version 3; use \'contentType\' instead';

            @trigger_error($message, E_USER_DEPRECATED);
        }

        $name         = rawurlencode($name);
        $driveLocator = "/drives/{$this->parentReference->driveId}";
        $itemLocator  = "/items/{$this->id}";
        $endpoint     = "$driveLocator$itemLocator:/$name:/content";

        $queryParams = $this
            ->parameterDirector
            ->buildPutContentQueryStringParameters($options);

        if (!empty($queryParams)) {
            $queryString = http_build_query($queryParams, '', '&', PHP_QUERY_RFC3986);
            $endpoint    = "$endpoint?$queryString";
        }

        $headerParams = $this
            ->parameterDirector
            ->buildPutContentHeaderParameters($options);

        $body = $content instanceof Stream ?
            $content
            : Psr7\stream_for($content);

        try {
            $response = $this
                ->graph
                ->createRequest('PUT', $endpoint)
                ->addHeaders($headerParams)
                ->attachBody($body)
                ->execute();

            $status = $response->getStatus();

            if ($status != 200 && $status != 201) {
                throw new \Exception("Unexpected status code produced by 'PUT $endpoint': $status");
            }

            $driveItem = $response->getResponseAsObject(DriveItem::class);

            return new self($this->graph, $driveItem, $this->parameterDirector);
        } catch (ClientException $exception) {
            $status = $exception
                ->getResponse()
                ->getStatusCode();

            if ($status == 409) {
                $message = sprintf(
                    'There is already a drive item named "%s" in this folder',
                    $name
                );

                throw new ConflictException($message);
            }

            throw $exception;
        }
    }

    /**
     * Creates an upload session to upload a large file in multiple ranges under
     * this folder drive item.
     *
     * This operation is supported only on folders (as opposed to files): it
     * fails if this `DriveItemProxy` instance does not refer to a folder.
     *
     * Uploading files using this method involves two steps:
     *     1. first, create the upload session for a given file using this
     *        method ;
     *     2. then, complete it using
     *        {@see \Krizalys\Onedrive\Proxy\UploadSessionProxy::complete() complete()}
     *        on the instance it returns.
     *
     * For example:
     *
     * ```php
     * $driveItem->upload(
     *     'file.txt',
     *     'Some content',
     *     ['contentType' => 'text/plain']
     * );
     * // => Text file 'file.txt' created under $driveItem.
     *
     * $uploadSession1 = $driveItem->startUpload(
     *     'file.txt',
     *     'Some other content',
     *     [
     *         'conflictBehavior' => ConflictBehavior::RENAME,
     *         'type'             => 'text/plain',
     *     ]
     * );
     *
     * $childDriveItem = $uploadSession1->complete();
     * // => Text file 'file 1.txt' created under $driveItem.
     * ```
     *
     * @param string $name
     *        The name.
     * @param string|resource|\GuzzleHttp\Psr7\Stream $content
     *        The content.
     * @param mixed[string] $options
     *        The options. See UploadSession::__construct for supported options.
     *
     * @return UploadSessionProxy
     *         The upload session created.
     *
     * @since 2.1.0
     *
     * @api
     *
     * @link https://docs.microsoft.com/en-us/onedrive/developer/rest-api/api/driveitem_createuploadsession?view=odsp-graph-online
     *       Upload large files with an upload session
     *
     * @todo Support content type in options.
     */
    public function startUpload($name, $content, array $options = [])
    {
        $name         = rawurlencode($name);
        $driveLocator = "/drives/{$this->parentReference->driveId}";
        $itemLocator  = "/items/{$this->id}";
        $endpoint     = "$driveLocator$itemLocator:/$name:/createUploadSession";

        $bodyParams = $this
            ->parameterDirector
            ->buildPostCreateUploadSessionBodyParameters($options);

        try {
            $response = $this
                ->graph
                ->createRequest('POST', $endpoint)
                ->attachBody($bodyParams)
                ->execute();

            $status = $response->getStatus();

            if ($status != 200) {
                throw new \Exception("Unexpected status code produced by 'POST $endpoint': $status");
            }

            $uploadSession = $response->getResponseAsObject(UploadSession::class);

            return new UploadSessionProxy(
                $this->graph,
                $uploadSession,
                $content,
                $this->parameterDirector,
                $options
            );
        } catch (ClientException $exception) {
            $status = $exception
                ->getResponse()
                ->getStatusCode();

            if ($status == 409) {
                $message = sprintf(
                    'There is already a drive item named "%s" in this folder',
                    $name
                );

                throw new ConflictException($message);
            }

            throw $exception;
        }
    }

    /**
     * Downloads this file drive item.
     *
     * This operation is supported only on files (as opposed to folders): it
     * fails if this `DriveItemProxy` instance does not refer to a file.
     *
     * @return \GuzzleHttp\Psr7\Stream
     *         The content.
     *
     * @since 2.0.0
     *
     * @api
     *
     * @link https://docs.microsoft.com/en-us/onedrive/developer/rest-api/api/driveitem_get_content?view=odsp-graph-online
     *       Download the contents of a DriveItem
     */
    public function download()
    {
        $driveLocator = "/drives/{$this->parentReference->driveId}";
        $itemLocator  = "/items/{$this->id}";
        $endpoint     = "$driveLocator$itemLocator/content";

        return $this
            ->graph
            ->createRequest('GET', $endpoint)
            ->setReturnType(Stream::class)
            ->execute();
    }

    /**
     * Renames this drive item.
     *
     * When renaming a drive item, its description may also be changed.
     *
     * ```php
     * $driveItem = $driveItem->rename(
     *     'new-name.txt',
     *     ['description' => 'New description']
     * );
     * ```
     *
     * @param string $name
     *        The name.
     * @param mixed[string] $options
     *        The options. Supported options:
     *          - `'description'` *(string)*: the new description to set after
     *            the drive item is renamed.
     *
     * @return DriveItemProxy
     *         The drive item renamed.
     *
     * @since 2.0.0
     *
     * @api
     *
     * @link https://docs.microsoft.com/en-us/onedrive/developer/rest-api/api/driveitem_move?view=odsp-graph-online
     *       Move a DriveItem to a new folder
     */
    public function rename($name, array $options = [])
    {
        $driveLocator = "/drives/{$this->parentReference->driveId}";
        $itemLocator  = "/items/{$this->id}";
        $endpoint     = "$driveLocator$itemLocator";

        $body = array_replace_recursive([
            'name' => (string) $name,
        ], $options);

        $response = $this
            ->graph
            ->createRequest('PATCH', $endpoint)
            ->attachBody($body)
            ->execute();

        $status = $response->getStatus();

        if ($status != 200) {
            throw new \Exception("Unexpected status code produced by 'PATCH $endpoint': $status");
        }

        $driveItem = $response->getResponseAsObject(DriveItem::class);

        return new self($this->graph, $driveItem, $this->parameterDirector);
    }

    /**
     * Moves this drive item.
     *
     * The `$destinationItem` instance must refer to a folder.
     *
     * When moving a drive item, its name may also be changed.
     *
     * ```php
     * $driveItem = $driveItem->move(
     *     $parentDriveitem,
     *     ['name' => 'new-name.txt']
     * );
     * ```
     *
     * @param DriveItemProxy $destinationItem
     *        The destination item.
     * @param mixed[string] $options
     *        The options. Supported options:
     *          - `'name'` *(string)*: the new name to set after the drive item
     *            is moved.
     *
     * @return DriveItemProxy
     *         The drive item.
     *
     * @since 2.0.0
     *
     * @api
     *
     * @link https://docs.microsoft.com/en-us/onedrive/developer/rest-api/api/driveitem_move?view=odsp-graph-online
     *       Move a DriveItem to a new folder
     */
    public function move(self $destinationItem, array $options = [])
    {
        $driveLocator = "/drives/{$this->parentReference->driveId}";
        $itemLocator  = "/items/{$this->id}";
        $endpoint     = "$driveLocator$itemLocator";

        $body = array_replace_recursive([
            'parentReference' => [
                'id' => $destinationItem->id,
            ],
        ], $options);

        $response = $this
            ->graph
            ->createRequest('PATCH', $endpoint)
            ->attachBody($body)
            ->execute();

        $status = $response->getStatus();

        if ($status != 200) {
            throw new \Exception("Unexpected status code produced by 'PATCH $endpoint': $status");
        }

        $driveItem = $response->getResponseAsObject(DriveItem::class);

        return new self($this->graph, $driveItem, $this->parameterDirector);
    }

    /**
     * Copies this file drive item.
     *
     * This operation is supported only on files (as opposed to folders): it
     * fails if this `DriveItemProxy` instance does not refer to a file.
     *
     * Additionally, the `$destinationItem` instance must refer to a folder.
     *
     * When copying a file, the name of the copy may also be changed. A new name
     * is required if copying to the same folder.
     *
     * ```php
     * $driveItem = $driveItem->copy(
     *     $parentDriveitem,
     *     ['name' => 'new-name.txt']
     * );
     * ```
     *
     * Copying folders is not directly supported by OneDrive. To copy a whole
     * folder and its children, applications can explicitly create an empty
     * folder, using
     * {@see \Krizalys\Onedrive\Proxy\DriveItemProxy::createFolder() createFolder()},
     * and copy the children from the original folder to the new folder, using
     * {@see \Krizalys\Onedrive\Proxy\DriveItemProxy::copy() copy()}. This
     * process can be repeated recursively if support for multiple levels
     * of children is needed.
     *
     * @param DriveItemProxy $destinationItem
     *        The destination item.
     * @param mixed[string] $options
     *        The options. Supported options:
     *          - `'name'` *(string)*: the name of the copied file.
     *
     * @return string
     *         The progress URI.
     *
     * @since 2.0.0
     *
     * @api
     *
     * @link https://docs.microsoft.com/en-us/onedrive/developer/rest-api/api/driveitem_copy?view=odsp-graph-online
     *       Copy a DriveItem
     *
     * @todo Support asynchronous Graph operation.
     */
    public function copy(self $destinationItem, array $options = [])
    {
        $driveLocator = "/drives/{$this->parentReference->driveId}";
        $itemLocator  = "/items/{$this->id}";
        $endpoint     = "$driveLocator$itemLocator/copy";

        $body = array_replace_recursive([
            'parentReference' => [
                'id' => $destinationItem->id,
            ],
        ], $options);

        $response = $this
            ->graph
            ->createRequest('POST', $endpoint)
            ->attachBody($body)
            ->execute();

        $status = $response->getStatus();

        if ($status != 202) {
            throw new \Exception("Unexpected status code produced by 'POST $endpoint': $status");
        }

        $headers = $response->getHeaders();

        return $headers['Location'][0];
    }

    /**
     * Creates a sharing link to this drive item.
     *
     * See {@see \Krizalys\Onedrive\Constant\SharingLinkType
     * SharingLinkType} for supported values for the parameter `$type`.
     *
     * See {@see \Krizalys\Onedrive\Constant\SharingLinkScope
     * SharingLinkScope} for supported values for the option `'scope'`.
     *
     * @param string $type
     *        The type.
     * @param mixed[string] $options
     *        The options. Supported values:
     *          - `'scope'` *(string)*: the scope.
     *
     * @return PermissionProxy
     *         The permission.
     *
     * @since 2.4.0
     *
     * @api
     *
     * @link https://docs.microsoft.com/en-us/onedrive/developer/rest-api/api/driveitem_createlink?view=odsp-graph-online
     *       Create a sharing link for a DriveItem
     */
    public function createLink($type, array $options = [])
    {
        $driveLocator = "/drives/{$this->parentReference->driveId}";
        $itemLocator  = "/items/{$this->id}";
        $endpoint     = "$driveLocator$itemLocator/createLink";

        $body = [
            'type' => $type,
        ];

        $response = $this
            ->graph
            ->createRequest('POST', $endpoint)
            ->attachBody($body)
            ->execute();

        $status = $response->getStatus();

        if ($status != 200 && $status != 201) {
            throw new \Exception("Unexpected status code produced by 'POST $endpoint': $status");
        }

        $permission = $response->getResponseAsObject(Permission::class);

        return new PermissionProxy($this->graph, $permission);
    }
}
