<?php

/**
 * Production Cache Configuration
 * 
 * This file provides production-ready cache configurations
 * for different deployment scenarios.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Production Cache Recommendations
    |--------------------------------------------------------------------------
    |
    | For production environments, use one of these cache drivers:
    |
    | 1. Redis (Recommended for high-traffic applications)
    |    - Set CACHE_STORE=redis in .env
    |    - Requires Redis server installation
    |    - Best performance and scalability
    |
    | 2. Memcached (Good for medium-traffic applications)
    |    - Set CACHE_STORE=memcached in .env
    |    - Requires Memcached server installation
    |    - Good performance, shared across servers
    |
    | 3. Database (Fallback for simple deployments)
    |    - Set CACHE_STORE=database in .env
    |    - Uses existing database connection
    |    - Slower than Redis/Memcached but reliable
    |
    | 4. File (NOT RECOMMENDED for production)
    |    - Only use for development/testing
    |    - Poor performance with multiple servers
    |    - File system I/O bottlenecks
    |
    */

    'recommended_drivers' => [
        'high_traffic' => 'redis',
        'medium_traffic' => 'memcached', 
        'low_traffic' => 'database',
        'development' => 'array'
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration for Different Environments
    |--------------------------------------------------------------------------
    |
    | Environment-specific cache settings
    |
    */

    'environments' => [
        'local' => [
            'driver' => 'array',
            'ttl' => 60, // 1 minute
            'description' => 'Fast in-memory cache for development'
        ],
        
        'staging' => [
            'driver' => 'database',
            'ttl' => 300, // 5 minutes
            'description' => 'Database cache for staging environment'
        ],
        
        'production' => [
            'driver' => 'redis',
            'ttl' => 600, // 10 minutes
            'description' => 'Redis cache for production (requires Redis server)'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache TTL Recommendations
    |--------------------------------------------------------------------------
    |
    | Time-to-live settings for different data types
    |
    */

    'ttl_recommendations' => [
        'static_data' => 3600,      // 1 hour (clubs list, schools)
        'user_sessions' => 1800,    // 30 minutes (dashboard data)
        'query_results' => 300,     // 5 minutes (search results)
        'reports' => 600,           // 10 minutes (generated reports)
        'config' => 7200,           // 2 hours (application config)
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Monitoring
    |--------------------------------------------------------------------------
    |
    | Cache performance metrics to monitor
    |
    */

    'monitoring' => [
        'hit_ratio_target' => 0.8,  // 80% cache hit ratio
        'max_memory_usage' => '512MB',
        'max_connections' => 100,
        'timeout' => 5, // seconds
    ]
];
