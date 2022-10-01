<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
             
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv="x-dns-prefetch-control" content="off"/>
        <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" id="viewport">
        <meta name="robots" content="noindex"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
        <link type="text/css" 
          rel="stylesheet" media="screen" href="https://assets.tumblr.com/assets/styles/global.css?_v=575a180469105f55c4d019c258cbec57">        <link type="text/css"rel="stylesheet" media="screen" href="https://assets.tumblr.com/assets/styles/verify_email.css?_v=fadac954598faa4ab265a9b69f6d9452">        <title>JIMS Welcome Page</title>
    
<style type="text/css">
  .card {
  background: #fff;
  border-radius: 10px;
  display: inline-block;
  /*height: 300px;*/
  margin: 1rem;
  position: relative;
  width: 500px;
  padding: 20px 20px;
  display: block;

}

.card:hover {
  box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
}

.card {
  box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
}

.card {
  color: #333;
  
}

</style>



    </head>
    <body class="callout_page device_other" >
        <table height="100%" width="100%">
            <tr>
                <td align="center" valign="middle">
                    <h1 class="callout_row header" id="l10n_title">
                        <span class="callout_title">Welcome to Jagan Institute of Management Studies</span>
                    </h1>

                    <div class="callout_row callout_row_text">
                        Hi {{ $form->name }}, We have created a web portal for you where you can access all the features of Aspiring Students.<br>
                        To enter the portal you need to login with below credentials
                        <br><br>
                        
                        <div class="card">
                          Portal Link: <br><a href="http://jimsinfo.org/microsite/studentzone">http://jimsinfo.org/microsite/studentzone</a>
                          <br><br>
                          Username:  {{ $logindetails->email }} <br>
                          Password: {{ $logindetails->password }}
                          <br> 

                          
                        </div>
                      </div>

                    <div class="callout_row app_buttons">
                      <a href="http://jimsinfo.org/microsite/studentzone" class="download_ios chrome blue">
                          
                         Visit Portal     </a>
                    </div>
                     
                
                                                         
                </td>
            </tr>
        </table>
         </body>
</html>
