# Silverstripe Quantum

Content builder using the CMS interface

## Overview

Quantum is a new approach that allows content to be created without a developer creating a Model class each time.

A CMS user will create a new Collection, that will then define the field types that can be populated and returned at an api endpoint.

### Collection

Defines the route to the api endpointand the fields available for the data (atoms), list of atoms that are in this collection, with add-on module you can add more refined permissions

### Datum

data that the api returns, the fields are defined in the collection, add versions module to allow changes to be drafted before publication
