<?php

namespace restdoc;

use restdoc\language\DefaultLanguage;
use restdoc\parser\arrayble\{
    ApiBodyParser,
    ApiDefineParser,
    ApiDeprecatedParser,
    ApiDescriptionParser,
    ApiErrorExampleParser,
    ApiErrorParser,
    ApiExampleParser,
    ApiGroupParser,
    ApiHeaderExampleParser,
    ApiHeaderParser,
    ApiNameParser,
    ApiParamParser,
    ApiParamExampleParser,
    ApiParser,
    ApiPermissionParser,
    ApiPrivateParser,
    ApiQueryParser,
    ApiSampleRequestParser,
    ApiSuccessExampleParser,
    ApiSuccessParser,
    ApiUseParser,
    ApiVersionParser,
};

/**
 * Class RunApp
 */
class RunApp
{
    public static function createDoc() {
        $options = self::initOptions();



        // Line-Ending.
        if ($options->lineEnding) {
            if ($options->lineEnding === 'CRLF')
                $options->lineEnding = "\r\n"; // win32
            else if ($options->lineEnding === 'CR')
                $options->lineEnding = "\r"; // darwin
            else
                $options->lineEnding = "\n"; // linux
        }

        try {
            $appSettings = new AppSettings();
           // $appSettings->options = $options;
            $appSettings->parser = self::initParser();
            $appSettings->worker = new Worker();

            $app = new App($appSettings, new PackageInfo());

            $app->parse($options);
        } catch (\Throwable | \Error $e) {
            throw $e;
        }
    }

    /**
     * @return Options
     */
    private static function initOptions() {
        $options = new Options();
        $options->src = __DIR__ . '/test';
        $options->encoding = 'utf8';
        $options->includeFilters = ['/.*\\.(clj|cls|coffee|cpp|cs|dart|erl|exs?|go|groovy|ino?|java|js|jsx|kt|litcoffee|lua|mjs|p|php?|pl|pm|py|rb|scala|ts|vue)$/'];
        $options->dest = 'php_apidoc/';
        $options->config = __DIR__ . '/apidoc/uk';
        $options->template = __DIR__ . '/apidoc/template/';

        return $options;
    }

    /**
     * @return Parser
     */
    private static function initParser() {
        $parser = new Parser(new FindFiles());
        $parser->addParser('apibody', new ApiBodyParser(new ApiParamParser()));
        $parser->addParser('apidefine', new ApiDefineParser(new ApiParamParser()));
        $parser->addParser('apideprecated', new ApiDeprecatedParser());
        $parser->addParser('apidescription', new ApiDescriptionParser());
        $parser->addParser('apierrorexample', new ApiErrorExampleParser(new ApiExampleParser()));
        $parser->addParser('apierror', new ApiErrorParser(new ApiParamParser()));
        $parser->addParser('apiexample', new ApiExampleParser());
        $parser->addParser('apigroup', new ApiGroupParser());
        $parser->addParser('apiheaderexample', new ApiHeaderExampleParser(new ApiExampleParser()));
        $parser->addParser('apiheader', new ApiHeaderParser(new ApiParamParser()));
        $parser->addParser('apiname', new ApiNameParser());
        $parser->addParser('apiparam', new ApiParamParser());
        $parser->addParser('apiparamexample', new ApiParamExampleParser(new ApiExampleParser()));
        $parser->addParser('api', new ApiParser());
        $parser->addParser('apipermission', new ApiPermissionParser(new ApiUseParser()));
        $parser->addParser('apiprivate', new ApiPrivateParser());
        $parser->addParser('apiquery', new ApiQueryParser(new ApiParamParser()));
        $parser->addParser('apisamplerequest', new ApiSampleRequestParser());
        $parser->addParser('apisuccessexample', new ApiSuccessExampleParser(new ApiExampleParser()));
        $parser->addParser('apisuccess', new ApiSuccessParser(new ApiParamParser()));
        $parser->addParser('apiuse', new ApiUseParser());
        $parser->addParser('apiversion', new ApiVersionParser());

        $parser->addLanguage('default', new DefaultLanguage());

        return $parser;
    }
}