<?php

namespace Test\Unit\Krizalys\Onedrive\Proxy;

use GuzzleHttp\Psr7;
use Krizalys\Onedrive\Parameter\DriveItemParameterDirectorInterface;
use Krizalys\Onedrive\Proxy\DriveItemProxy;
use Krizalys\Onedrive\Proxy\UploadSessionProxy;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Http\GraphRequest;
use Microsoft\Graph\Http\GraphResponse;
use Microsoft\Graph\Model\DriveItem;
use Microsoft\Graph\Model\UploadSession;
use PHPUnit\Framework\TestCase;

class UploadSessionProxyTest extends TestCase
{
    public function testExpirationDateTimeShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $dateTime = new \DateTime();

        $uploadSession = $this->createMock(UploadSession::class);
        $uploadSession->method('getExpirationDateTime')->willReturn($dateTime);

        $driveItemParameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new UploadSessionProxy($graph, $uploadSession, '', $driveItemParameterDirector);
        $this->assertSame($dateTime, $sut->expirationDateTime);
    }

    public function testNextExpectedRangesShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $uploadSession = $this->createMock(UploadSession::class);
        $uploadSession->method('getNextExpectedRanges')->willReturn(['0-1', '2-3']);

        $driveItemParameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new UploadSessionProxy($graph, $uploadSession, '', $driveItemParameterDirector);
        $this->assertIsArray($sut->nextExpectedRanges);
        $this->assertSame(['0-1', '2-3'], $sut->nextExpectedRanges);
    }

    public function testUploadUrlShouldReturnExpectedValue()
    {
        $graph = $this->createMock(Graph::class);

        $uploadSession = $this->createMock(UploadSession::class);
        $uploadSession->method('getUploadUrl')->willReturn('http://uplo.ad/url');

        $driveItemParameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $sut = new UploadSessionProxy($graph, $uploadSession, '', $driveItemParameterDirector);
        $this->assertIsString('string', $sut->uploadUrl);
        $this->assertSame('http://uplo.ad/url', $sut->uploadUrl);
    }

    public function testCompleteWithStringContentShouldReturnExpectedValue()
    {
        $item = $this->createMock(DriveItem::class);
        $item->method('getId')->willReturn('123abc');

        $response = $this->createMock(GraphResponse::class);
        $response->method('getStatus')->will($this->onConsecutiveCalls(202, 201));
        $response->method('getResponseAsObject')->willReturn($item);

        $request = $this->createMock(GraphRequest::class);
        $request->method('addHeaders')->willReturnSelf();
        $request->method('attachBody')->willReturnSelf();
        $request->method('execute')->willReturn($response);

        $graph = $this->createMock(Graph::class);
        $graph->method('createRequest')->willReturn($request);

        $uploadSession = $this->createMock(UploadSession::class);

        $content = str_repeat('1', 327680 + 1);

        $driveItemParameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $options = [
            'range_size' => 327680,
        ];

        $sut    = new UploadSessionProxy($graph, $uploadSession, $content, $driveItemParameterDirector, $options);
        $actual = $sut->complete();
        $this->assertInstanceOf(DriveItemProxy::class, $actual);
        $this->assertSame('123abc', $actual->id);
    }

    public function testCompleteWithStreamContentShouldReturnExpectedValue()
    {
        $item = $this->createMock(DriveItem::class);
        $item->method('getId')->willReturn('123abc');

        $response = $this->createMock(GraphResponse::class);
        $response->method('getStatus')->will($this->onConsecutiveCalls(202, 201));
        $response->method('getResponseAsObject')->willReturn($item);

        $request = $this->createMock(GraphRequest::class);
        $request->method('addHeaders')->willReturnSelf();
        $request->method('attachBody')->willReturnSelf();
        $request->method('execute')->willReturn($response);

        $graph = $this->createMock(Graph::class);
        $graph->method('createRequest')->willReturn($request);

        $uploadSession = $this->createMock(UploadSession::class);

        $content = Psr7\stream_for(str_repeat('1', 327680 + 1));

        $driveItemParameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $options = [
            'range_size' => 327680,
        ];

        $sut    = new UploadSessionProxy($graph, $uploadSession, $content, $driveItemParameterDirector, $options);
        $actual = $sut->complete();
        $this->assertInstanceOf(DriveItemProxy::class, $actual);
        $this->assertSame('123abc', $actual->id);
    }

    public function testCompleteContentShouldSendExpectedHeaders()
    {
        $item = $this->createMock(DriveItem::class);
        $item->method('getId')->willReturn('123abc');

        $response = $this->createMock(GraphResponse::class);
        $response->method('getStatus')->will($this->onConsecutiveCalls(202, 201));
        $response->method('getResponseAsObject')->willReturn($item);

        $request = $this->createMock(GraphRequest::class);
        $request->method('attachBody')->willReturnSelf();
        $request->method('execute')->willReturn($response);

        $request
            ->expects($this->exactly(2))
            ->method('addHeaders')
            ->withConsecutive(
                [$this->callback(function ($headers) {
                    return
                        $headers['Content-Type'] == 'text/plain'
                        && $headers['Content-Length'] == '655360'
                        && $headers['Content-Range'] == 'bytes 0-655359/655361';
                })],
                [$this->callback(function ($headers) {
                    return
                        $headers['Content-Type'] == 'text/plain'
                        && $headers['Content-Length'] == '1'
                        && $headers['Content-Range'] == 'bytes 655360-655360/655361';
                })]
            )
            ->willReturnSelf();

        $graph = $this->createMock(Graph::class);
        $graph->method('createRequest')->willReturn($request);

        $uploadSession = $this->createMock(UploadSession::class);

        $content = str_repeat('1', 655360 + 1);

        $driveItemParameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $options = [
            'type'       => 'text/plain',
            'range_size' => 655360,
        ];

        $sut = new UploadSessionProxy($graph, $uploadSession, $content, $driveItemParameterDirector, $options);
        $sut->complete();
    }

    public function testCompleteShouldThrowFileNotCreatedException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('OneDrive did not create a drive item for the uploaded file');
        $item = $this->createMock(DriveItem::class);
        $item->method('getId')->willReturn('123abc');

        $response = $this->createMock(GraphResponse::class);
        $response->method('getStatus')->will($this->onConsecutiveCalls(202, 202));
        $response->method('getResponseAsObject')->willReturn($item);

        $request = $this->createMock(GraphRequest::class);
        $request->method('addHeaders')->willReturnSelf();
        $request->method('attachBody')->willReturnSelf();
        $request->method('execute')->willReturn($response);

        $graph = $this->createMock(Graph::class);
        $graph->method('createRequest')->willReturn($request);

        $uploadSession = $this->createMock(UploadSession::class);

        $content = str_repeat('1', 327680 + 1);

        $driveItemParameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $options = [
            'range_size' => 327680,
        ];

        $sut = new UploadSessionProxy($graph, $uploadSession, $content, $driveItemParameterDirector, $options);
        $sut->complete();
    }

    public function testCompleteShouldThrowUnexpectedStatusCodeException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unexpected status code produced by \'PUT http://uplo.ad/url\': 503');
        $item = $this->createMock(DriveItem::class);
        $item->method('getId')->willReturn('123abc');

        $response = $this->createMock(GraphResponse::class);
        $response->method('getStatus')->will($this->onConsecutiveCalls(202, 503));
        $response->method('getResponseAsObject')->willReturn($item);

        $request = $this->createMock(GraphRequest::class);
        $request->method('addHeaders')->willReturnSelf();
        $request->method('attachBody')->willReturnSelf();
        $request->method('execute')->willReturn($response);

        $graph = $this->createMock(Graph::class);
        $graph->method('createRequest')->willReturn($request);

        $uploadSession = $this->createMock(UploadSession::class);
        $uploadSession->method('getUploadUrl')->willReturn('http://uplo.ad/url');

        $content = str_repeat('1', 327680 + 1);

        $driveItemParameterDirector = $this->createMock(DriveItemParameterDirectorInterface::class);

        $options = [
            'range_size' => 327680,
        ];

        $sut = new UploadSessionProxy($graph, $uploadSession, $content, $driveItemParameterDirector, $options);
        $sut->complete();
    }
}
