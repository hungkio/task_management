# Page

cách get refresh token:
https://www.dropbox.com/oauth2/authorize?client_id=<APP_KEY>&token_access_type=offline&response_type=code
sẽ lấy được code
sử dụng code cho request thông qua postman, tham số dạng urlencoded, header content-type json: 
https://api.dropboxapi.com/oauth2/token 
client_id=
client_secret=
grant_type=authorization_code
code=
response trả về sẽ chứa refresh token
