Caching services
================

We are using the Drupal Cache Component with a Redis backend.  
- Please use the `CacheHandler` service for any access to the caching layer.  
- Please use the `CacheKeyGenerator` service to generate reusable cache parameters, to stay DRY.  

Classes
-------

- `CacheHandler`: main service to handle caching and clearing.
- `CacheKeyGenerator`: service to generate cache keys and parameters.
- `CacheKeyPrefixer`: service to prefix cache keys according to the context.
- `CacheKeySanitizer`: service to sanitize cache keys.

Usage
-------

- `CacheHandler::get($cid, $parameters)`: Get data from cache by id (see CacheKeyGenerator for details).  
- `CacheHandler::set($cid, $data, $parameters)`: Set data into the cache.  
  
Please see CacheHandler for more details & methods.