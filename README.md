# Silverstripe Quantum

API builder using the CMS intnerface

## Contents
The default way to add data in silverstripe involves developers to create Models and Model Admins. 
Quantum is a new approach that allows Content to be created without a specific Model class.

First a user will create a new DataSource, that will then define the fields the data will have and a api route point.
Then create a DataEntity that loads in the defined fields ready for input.

We store the Values of the DataEntity in a JSON string to be served by the API request
