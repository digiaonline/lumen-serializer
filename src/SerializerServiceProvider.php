<?php namespace Nord\Lumen\Serializer;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Illuminate\Contracts\Container\Container;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Support\ServiceProvider;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;

class SerializerServiceProvider extends ServiceProvider
{

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->registerContainerBindings($this->app, $this->app['config']);
        $this->registerFacades();
        $this->registerAnnotationLoader();
    }


    /**
     * @param Container $container
     * @param ConfigRepository $config
     */
    protected function registerContainerBindings(Container $container, ConfigRepository $config)
    {
        $container->singleton('JMS\Serializer\Serializer', function () use ($config) {
            return $this->createSerializer($config['serializer'], $config['app.debug']);
        });
    }


    /**
     *
     */
    protected function registerFacades()
    {
        class_alias('Nord\Lumen\Serializer\Facades\Serializer', 'Serializer');
    }


    /**
     *
     */
    protected function registerAnnotationLoader()
    {
        AnnotationRegistry::registerLoader('class_exists');
    }


    /**
     * @param array $config
     * @param bool  $debug
     *
     * @return Serializer
     */
    protected function createSerializer(array $config, $debug)
    {
        $builder = new SerializerBuilder;

        if (isset($config['cache_dir'])) {
            $builder->setCacheDir($config['cache_dir']);
        }

        if (isset($config['default_handlers'])) {
            $builder->addDefaultHandlers();
        }

        if (isset($config['handlers'])) {
            foreach ($config['handlers'] as $handler) {
                $builder->configureHandlers($handler);
            }
        }

        if (isset($config['event_listeners'])) {
            foreach ($config['event_listeners'] as $handler) {
                $builder->configureListeners($handler);
            }
        }

        $builder->addMetadataDirs(array_get($config, 'paths', [base_path('app')]));
        $builder->setDebug($debug);

        return $builder->build();
    }
}
