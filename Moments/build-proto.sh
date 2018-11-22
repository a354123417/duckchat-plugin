
cd duckchat-proto

protoc --php_out=../lib/proto/ core/message.proto
protoc --php_out=../lib/proto/ core/net.proto
protoc --php_out=../lib/proto/ core/plugin.proto
protoc --php_out=../lib/proto/ core/site.proto
protoc --php_out=../lib/proto/ core/plugin.proto



protoc --php_out=../lib/proto/ platform/core.proto
protoc --php_out=../lib/proto/ platform/api_platform_login.proto
protoc --php_out=../lib/proto/ platform/api_platform_check.proto
protoc --php_out=../lib/proto/ platform/api_site_register.proto
protoc --php_out=../lib/proto/ platform/api_session_verify.proto
protoc --php_out=../lib/proto/ platform/api_sms_verifyCode.proto




