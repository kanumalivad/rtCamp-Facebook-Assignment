# rtCamp-Facebook-Assignment

PART 1 :

First you need to login with your facebook. You will be asked to give permission for accessing your email,user_photos,cover_photo. After you give the required permissions you will be redirected to the index page.

PART 2 :

On the index page Albums are displayed with album name. When clicked on album name, a fullscreen slide show starts displaying all the photos inside that album.

A Download button is available for each album below the album name. On clicking the button, jquery(Ajax) processes PHP script to collect photos for that album, Zip them and shows a link to download the Zip Folder.On clicking on that link the zipped folder will get downloaded.

An checkbox is displayed for each album above the album name. A "Download Selected" link is displayed at top. On clicking the link, jquery(Ajax) processes PHP script to collect photos for that album, Zip the selected albums into a folder named "Facebook" and shows a link to download the Zip Folder.On clicking on that link the zipped folder will get downloaded.

A "Download All" link is displayed at top. On clicking the link, jquery(Ajax) processes PHP script to collect photos for all album, Zip all the albums into a "Facebook" named folder and shows a link to download the Zip Folder.On clicking on that link the zipped folder will get downloaded.

All the time while albums are download and processed into zip, a loading spinner is shown.

PART 3 :

NOTE : For moving albums to Google Drive, you need to login to your google account first. You will see a "login" link , on clicking the link you will be asked to login with your google account, after that you will be asked for permissions, after approving the required permissions you will be redirected to the index page.

A "Move" button is displayed for each album. When user clicks on "Move" button, jquery(Ajax) processes PHP script to collect photos for that album and upload into Google Drive into a folder with the name as of the album name under a main folder with name as Facebook_{User_Name}_Album.

An checkbox is displayed for each album above the album name. A "Move Selected" link is displayed at top. When user clicks on "Move Selected" link, jquery(Ajax) processes PHP script to collect photos for those selected albums and upload into Google Drive into a folder with the name as of the album name under a main folder with name as Facebook_{User_Name}_Album.

A "Move All" link is displayed at top. When user clicks on "Move all" link, jquery(Ajax) processes PHP script to collect photos for all the albums and upload into Google Drive into a folder with the name as of the album name under a main folder with name as Facebook_{User_Name}_Album.

All the time while albums are processed to move, a loading spinner is shown.

Library Used:
Facebook PHP SDK
Google Drive API
Bootstrap
