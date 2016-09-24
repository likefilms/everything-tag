require 'yt'
#require 'youtube_it'

Yt.configure do |config|
  #config.client_id = '778045936909-1mg1jvinj933j97uuamuamr510hcn70u.apps.googleusercontent.com'
  #config.client_secret = 'DzyN-s3L7PQvx5iCvTf0a5Fs'
  config.api_key = 'AIzaSyBw1GxAq4UzBoaFpUvxoBK_i6dXWBgthy8'
  config.log_level = :debug
end


video = Yt::Video.new id: 'ywPGwmM6tiU'
puts video.description
