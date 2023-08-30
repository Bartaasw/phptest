import logging
from superset.security import SupersetSecurityManager

class CustomSsoSecurityManager(SupersetSecurityManager):

    def oauth_user_info(self, provider, response=None):
        logging.info("Oauth2 provider: {0}.".format(provider))
        if provider == 'WorkInConfidence':
            # As example, this line request a GET to base_url + '/' + userDetails with Bearer  Authentication,
    # and expects that authorization server checks the token, and response with user details
            logging.debug('=============== robie requesta ==============')
            
            response = self.appbuilder.sm.oauth_remotes[provider].get('/api.php/userDetails?XDEBUG_SESSION_START=PHPSTORM')
            logging.debug(type(response))
            logging.debug(response)
            logging.debug(response.status_code)
            logging.debug(response.json())
            logging.debug(type(response.json()))
            
            me = response.json()
            logging.debug("user_data: {0}".format(me))
            return { 'name' : me['name'], 'email' : me['email'], 'id' : me['id'], 'username': me['email'], 'first_name':'', 'last_name':''}
                # return { 'name' : 'Michalek', 'email' : 'michal.czarnota+a@ideaspot.pl', 'id' : 123, 'username' : 'Michalek', 'first_name':'', 'last_name':''}
    ...
