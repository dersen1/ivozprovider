#######################
Debian packages install
#######################

IvozProvider is designed to be installed and updated using Debian packages.
More exactly, the current release is ready to be installed on
`Debian Jessie 8 <https://www.debian.org/releases/jessie>`_.

It's recommended to use one of the `official installation guides
<https://www.debian.org/releases/jessie/installmanual>`_ to install the minimum
base system. The rest of required  dependencies will be installed automatically
with IvozProvider meta packages.

No matter if you are installing a  :ref:`StandAlone install` or a
:ref:`Distributed install`, it's required to configure Irontec debian
repositories.

****************************
APT Repository configuration
****************************

Right now, two different repositories are used for the latest IvozProvider
release (called oasis) and it's frontend Klear release (called chloe).

.. code-block:: console

    cd /etc/apt/sources.list.d
    echo deb http://packages.irontec.com/debian oasis main extra > ivozprovider.list
    echo deb http://packages.irontec.com/debian chloe main > klear.list

Optionally, we can add the repository key to check signed packages:

.. code-block:: console

    wget http://packages.irontec.com/public.key -q -O - | apt-key add -

**************************
Installing profile package
**************************

Once the repositories are configured, it will be required to select the proper
metapackage depending on the type of installation.

- For a :ref:`StandAlone install`:
    - ivozprovider

- For a :ref:`Distributed install`: one of the profile packages depending on the
  role the machine will perform.

    - ivozprovider-profile-data
    - ivozprovider-profile-proxy
    - ivozprovider-profile-portal
    - ivozprovider-profile-as

.. code-block:: console

    apt-get update
    apt-get install ivozprovider

***********************
Finish the installation
***********************
Distributed installation require a couple manual configuration based on the
roles that are performing. Check `finishing role configuration
<http://github.com/irontec/ivozprovider>`_ for more information.

Standalone installation have a menu that can be used to configure the basic
services used in IvozProvider. Most of the services are automatically configured
to work in the same machine with the default values.

This menu allows:

- Configure IP addresses for SIP proxies
- Default platform language
- Administrator MySQL database password

It's possible to change any of this values anytime by running:

.. code-block:: console

    dpkg-reconfigure ivozprovider


