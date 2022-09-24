# PHP-MQ

A simple and fast PHP message queueing framework utilizing Server-Sent Events (SSE).

## Overview

Most real-time web applications require WebSocket protocol to communicate between clients and servers. This method of two-way communications require persistent connections which PHP is not familiar with. Most PHP scripts are short-lived because its process lifetime just ends after each request is processed.

Eventhough there are several WebSocket implementations for PHP like [Ratchet](https://socketo.me/), it's a bit complicated to deploy especially on a shared hosting infrastructure with limited capabilities (like opening a port, using reverse proxy, etc). With PHP-MQ, we want to provide an easy solution that isn't neccessarily hostile on shared hosting infrastructures. Instead of using WebSockets, this framework is built on top of Server-Sent Events (SSE). The data is stored persistently by this framework inside a MySQL database.

## Features

- Easy to integrate (with **composer**)
- Easy to deploy (only needs **PHP** and **MySQL**, perfect for sites with shared hosting infrastructure)
- Easy to configure (only needs secret key configuration)
- Easy to adopt (with simple and encapsulated API calls)
- Fast and memory-efficient (uses MySQL indexing and performs cleanup regularly)

## Getting Started

To learn more about this architecture, please read the [Architecture](architecture.md) page.

When you are ready to incorporate into your project, please read the [Implementation](implementation.md) page.
