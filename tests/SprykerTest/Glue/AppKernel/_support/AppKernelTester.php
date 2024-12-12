<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\AppKernel;

use Codeception\Actor;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\AppKernel\Controller\AppConfigController;
use Spryker\Glue\AppKernel\Controller\AppDisconnectController;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(\SprykerTest\Glue\AppKernel\PHPMD)
 * @method \Spryker\Glue\AppKernel\AppKernelFactory getFactory()
 */
class AppKernelTester extends Actor
{
    use _generated\AppKernelTesterActions;

    /**
     * @param string $fixtureName
     *
     * @return string
     */
    protected function getFixturesPath(string $fixtureName): string
    {
        $pathTemplate = '%s/%s.json';

        return sprintf($pathTemplate, codecept_data_dir('Fixtures'), $fixtureName);
    }

    /**
     * @param string $fixtureName
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function createGlueRequestFromFixture(string $fixtureName): GlueRequestTransfer
    {
        $fixtureData = json_decode(file_get_contents($this->getFixturesPath($fixtureName)), true);

        $content = $fixtureData['content'] ?? [];

        if (
            isset($fixtureData['__configurationPayload'])
            && isset($content['data']['attributes']['configuration'])
        ) {
            $content['data']['attributes']['configuration'] = json_encode($fixtureData['__configurationPayload']);
        }

        return (new GlueRequestTransfer())
            ->setMeta($fixtureData['meta'] ?? [])
            ->setContent(json_encode($content));
    }

    /**
     * @return \Spryker\Glue\AppKernel\Controller\AppConfigController
     */
    public function createAppConfigController(): AppConfigController
    {
        return (new AppConfigController());
    }

    /**
     * @return \Spryker\Glue\AppKernel\Controller\AppDisconnectController
     */
    public function createAppDisconnectController(): AppDisconnectController
    {
        return (new AppDisconnectController());
    }
}
