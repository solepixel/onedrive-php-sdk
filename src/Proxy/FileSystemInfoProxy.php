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

declare(strict_types=1);

namespace Krizalys\Onedrive\Proxy;

use Microsoft\Graph\Graph;
use Microsoft\Graph\Model\FileSystemInfo;

/**
 * A proxy to a \Microsoft\Graph\Model\FileSystemInfo instance.
 *
 * @since 2.0.0
 *
 * @api
 *
 * @link https://github.com/microsoftgraph/msgraph-sdk-php/blob/dev/src/Model/FileSystemInfo.php
 */
class FileSystemInfoProxy extends EntityProxy
{
    /**
     * Constructor.
     *
     * @param Graph $graph
     *        The Microsoft Graph.
     * @param FileSystemInfo $fileSystemInfo
     *        The file system info.
     *
     * @since 2.0.0
     */
    public function __construct(Graph $graph, FileSystemInfo $fileSystemInfo)
    {
        parent::__construct($graph, $fileSystemInfo);
    }
}
