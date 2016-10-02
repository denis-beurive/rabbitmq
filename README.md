# Introduction

This repository contains an introduction to the use of [RabbitMQ](https://www.rabbitmq.com/).
 
Please note that the section that treats the installation of the server assumes that you are using _MAC OS X_.

We also include few notes for Windows' users.

# Installation

The procedure bellow assumes that you have installed the following tools:

* [GIT](https://git-scm.com/)
* [composer](https://getcomposer.org/)

Procedure:

    git clone git@github.com:denis-beurive/rabbitmq.git
    cd rabbitmq
    composer install

# RabbitMQ installation on MAC OS X

We recommend that you install RabbitMQ via [Mac Ports](https://www.macports.org/).

Make sure that RabbitMq is installed on your, MAC:

	$ sudo port installed | grep rabbitmq
	rabbitmq-server @3.5.7_0 (active)
	$ port info rabbitmq-server @3.5.7_0

If it is not installed, then install it:

	port search rabbitmq

This command above will return the name of the port that contains RabbitMQ (here `rabbitmq-server @3.5.7`). 

	sudo port install rabbitmq-server @3.5.7

Then, find out the server's location:

	$ port content rabbitmq-server @3.5.7_0ls | grep -E '/s?bin/'

Start the server:

	$ cd /opt/local/lib/rabbitmq/lib/rabbitmq_server-3.5.7/sbin
	$ sudo ./rabbitmq-server

When started, the server will print out the location of its LOG file:

	/opt/local/var/log/rabbitmq/rabbit@MBP-de-Denis.log
	/opt/local/var/log/rabbitmq/rabbit@MBP-de-Denis-sasl.log

Let's look at the LOG file to find out the location of the configuration file:

	$ cat /opt/local/var/log/rabbitmq/rabbit@MBP-de-Denis.log
	
	node           : rabbit@MBP-de-Denis
	home dir       : /Users/denisbeurive
	config file(s) : /opt/local/etc/rabbitmq/rabbitmq.config (not found)
	cookie hash    : FNiDg2f4bgqRmP33otitGw==
	log            : /opt/local/var/log/rabbitmq/rabbit@MBP-de-Denis.log
	sasl log       : /opt/local/var/log/rabbitmq/rabbit@MBP-de-Denis-sasl.log
	database dir   : /opt/local/var/lib/rabbitmq/mnesia/rabbit@MBP-de-Denis

We see that we have to create the configuration file `/opt/local/etc/rabbitmq/rabbitmq.config`.

We also get some interesting information:

	started TCP Listener on [::]:5672
	Creating user 'guest'
	Setting user tags for user 'guest' to [administrator]
	Setting permissions for 'guest' in '/' to '.*', '.*', '.*'

Let's create the configuration file `/opt/local/etc/rabbitmq/rabbitmq.config`, with this content:

	[
		{rabbit, [{tcp_listeners, [5672]}]}
	].

> Do not forget the last dot. This is not an typo.

Then restart the server to see if it finds its configuration file...

	sudo sh -c "echo '' > /opt/local/var/log/rabbitmq/rabbit@MBP-de-Denis.log" && \
	sudo sh -c "echo '' > /opt/local/var/log/rabbitmq/rabbit@MBP-de-Denis-sasl.log" && \
	sudo /opt/local/lib/rabbitmq/lib/rabbitmq_server-3.5.7/sbin/rabbitmq-server

By looking at the LOG file we know that RabbitMq has found its configuration file.

## Common commands

Stopping the server:

    sudo /opt/local/lib/rabbitmq/lib/rabbitmq_server-3.5.7/sbin/rabbitmqctl stop

Starting the server:

    sudo /opt/local/lib/rabbitmq/lib/rabbitmq_server-3.5.7/sbin/rabbitmq-server -detached

## Enabling the Admin tools

Enable the admin GUI:

    cd /opt/local/lib/rabbitmq/lib/rabbitmq_server-3.5.7/sbin
    rabbitmq-plugins enable rabbitmq_management

Then restart the server.

Download the CLI admin tool: `[http://localhost:15672/cli/](http://localhost:15672/cli/)`

Then install the Python script.

Rename it into `rabbitmqadmin.py`, and create the following script:

    #!/bin/sh
    
    SOURCE="${BASH_SOURCE[0]}"
    while [ -h "$SOURCE" ] ; do SOURCE="$(readlink "$SOURCE")"; done
    PWD="$( cd -P "$( dirname "$SOURCE" )" && pwd )"

    python $PWD/rabbitmqadmin.py $@

# Notes for windows.

Under Windows, the server is installed here:

    C:\Program Files\RabbitMQ Server\rabbitmq_server-3.6.5

## Enabling the admin GUI 

See: https://www.rabbitmq.com/management.html

    cd C:\Program Files\RabbitMQ Server\rabbitmq_server-3.6.5\sbin>
    rabbitmq-plugins enable rabbitmq_management

## Installing the command line interface

Then, open a page to: `[http://localhost:15672/](http://localhost:15672/)`

Get the CLI admin tool at the URL: `[http://localhost:15672/cli/](http://localhost:15672/cli/)`

Rename the file to `rabbitmqadmin.py`.

Then create the BAT file to run it:

    @ECHO OFF  
    set PWD=%~dp0
    python "%PWD%\\rabbitmqadmin.py" %*

