<?php

namespace phpdoc;

use phpdoc\filter\ApiErrorFilter;
use phpdoc\filter\ApiHeaderFilter;
use phpdoc\filter\ApiParamFilter;
use phpdoc\filter\ApiSuccessFilter;
use phpdoc\language\DefaultLanguage;
use phpdoc\parser\arrayble\{
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
use phpdoc\worker\ApiErrorStructureWorker;
use phpdoc\worker\ApiErrorTitleWorker;
use phpdoc\worker\ApiGroupWorker;
use phpdoc\worker\ApiHeaderStructureWorker;
use phpdoc\worker\ApiHeaderTitleWorker;
use phpdoc\worker\ApiNameWorker;
use phpdoc\worker\ApiParamTitleWorker;
use phpdoc\worker\ApiPermissionWorker;
use phpdoc\worker\ApiSampleRequestWorker;
use phpdoc\worker\ApiStructureWorker;
use phpdoc\worker\ApiSuccessStructureWorker;
use phpdoc\worker\ApiSuccessTitleWorker;
use phpdoc\worker\ApiUseWorker;

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
            $appSettings->options = $options;
            $appSettings->parser = self::initParser();
            $appSettings->worker = self::initWorker();
            $appSettings->filter = self::initFilter();

            $app = new App($appSettings, new PackageInfo());

            $api = $app->parse($options);
            if ($api === true) {
                //app.log.info('Nothing to do.');
                echo 'Nothing to do.', PHP_EOL;
                return true;
            }

            if ($api === false) {
                return false;
            }

            self::createOutputFiles($api, $options);

            echo 'Done.', PHP_EOL;

            return $api;
        } catch (\Throwable | \Error $e) {
            throw $e;
        }
    }

    private static function createOutputFiles(array $api, Options $options): void
    {
        /*if (app.options.simulate)
            app.log.warn('!!! Simulation !!! No file or dir will be copied or created.')*/;

        //app.log.verbose('create dir: ' + app.options.dest);
        //if ( ! app.options.simulate)
        if (!realpath($options->dest)) {
            mkdir($options->dest);
        }

        $options->dest = realpath($options->dest);


        //app.log.verbose('copy template ' + app.options.template + ' to: ' + app.options.dest);
        //if ( ! app.options.simulate)
            Filesystem::copyDirectory($options->template, $options->dest);

        // Write api_data
        //app.log.verbose('write json file: ' + app.options.dest + 'api_data.json');
        //if( ! app.options.simulate)
            file_put_contents($options->dest . '/api_data.json', $api['data'] . "\n");

        //app.log.verbose('write js file: ' + app.options.dest + 'api_data.js');
        //if( ! app.options.simulate)
            self::writeJSFIle($options->dest . '/api_data.js', '{ "api": ' . $api['data'] . ' }', $options);

        // Write api_project
        //app.log.verbose('write json file: ' + app.options.dest + 'api_project.json');
        //if( ! app.options.simulate)
            file_put_contents($options->dest . '/api_project.json', $api['project'] . "\n");

        //app.log.verbose('write js file: ' + app.options.dest + 'api_project.js');
        //if( ! app.options.simulate)
            self::writeJSFIle($options->dest . '/api_project.js', $api['project'], $options);

        // Write api_definitions
        //app.log.verbose('write json file: ' + app.options.dest + 'api_definitions.json');
        //if( ! app.options.simulate && ! app.options.copyDefinitions)
            //fs.writeFileSync(app.options.dest + './api_definition.json', api.definitions + '\n');

        //app.log.verbose('write js file: ' + app.options.dest + 'api_definitions.js');
        //if( ! app.options.simulate && ! app.options.copyDefinitions)
            //writeJSFIle(app.options.dest + './api_definition.js', api.definitions);
    }

    private static function writeJSFIle(string $dest, string $data, Options $options) {
        //if ( ! app.options.simulate) {
            switch ($options->mode) {
                case 'amd':
                default:
                    file_put_contents($dest,  "define(" . $data . ");\n");
                    break;
                case 'es':
                    file_put_contents($dest,  "export default " . $data . ";\n");
                    break;
                case 'commonJS':
                    file_put_contents($dest,  "module.exports = " . $data . ";\n");
                    break;
            }
       //}
    }

    /**
     * @return Options
     */
    private static function initOptions(): Options
    {
        $options = new Options();
        $options->src = __DIR__ . '/test';
        $options->encoding = 'utf8';
        $options->excludeFilters = ['/apidoc\\.config\\.js$/'];
        $options->includeFilters = ['/.*\\.(clj|cls|coffee|cpp|cs|dart|erl|exs?|go|groovy|ino?|java|js|jsx|kt|litcoffee|lua|mjs|p|php?|pl|pm|py|rb|scala|ts|vue)$/'];
        $options->dest = 'php_apidoc/';
        $options->config = __DIR__ . '/apidoc/uk';
        $options->template = __DIR__ . '/apidoc/template/';

        return $options;
    }

    /**
     * @return Parser
     */
    private static function initParser(): Parser
    {
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

    /**
     * @return Filter
     */
    private static function initFilter(): Filter
    {
        $filter = new Filter();

        $filter->addFilter('apierror', new ApiErrorFilter(new ApiParamFilter()));
        $filter->addFilter('apiheader', new ApiHeaderFilter(new ApiParamFilter()));
        $filter->addFilter('apiparam', new ApiParamFilter());
        $filter->addFilter('apisuccess', new ApiSuccessFilter(new ApiParamFilter()));

        return $filter;
    }

    /**
     * @return Worker
     */
    private static function initWorker(): Worker
    {
        $worker = new Worker();

        $worker->addWorker('apierrorstructure', new ApiErrorStructureWorker(new ApiUseWorker()));
        $worker->addWorker('apierrortitle', new ApiErrorTitleWorker(new ApiParamTitleWorker()));
        $worker->addWorker('apigroup', new ApiGroupWorker());
        $worker->addWorker('apiheaderstructure', new ApiHeaderStructureWorker(new ApiUseWorker()));
        $worker->addWorker('apiheadertitle', new ApiHeaderTitleWorker(new ApiParamTitleWorker()));
        $worker->addWorker('apiname', new ApiNameWorker());
        $worker->addWorker('apiparamtitle', new ApiParamTitleWorker());
        $worker->addWorker('apipermission', new ApiPermissionWorker());
        $worker->addWorker('apisamplerequest', new ApiSampleRequestWorker());
        $worker->addWorker('apistructure', new ApiStructureWorker(new ApiUseWorker()));
        $worker->addWorker('apisuccessstructure', new ApiSuccessStructureWorker(new ApiUseWorker()));
        $worker->addWorker('apisuccesstitle', new ApiSuccessTitleWorker(new ApiParamTitleWorker()));
        $worker->addWorker('apiuse', new ApiUseWorker());

        return $worker;
    }
}