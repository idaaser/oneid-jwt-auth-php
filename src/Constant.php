<?php
//namespace oneid\jwt_auth;

# 生成token的属性名称
const ATTRIBUTE_EXPIRE_TIME = "exp";
const ATTRIBUTE_ISSUER = "iss";
const ATTRIBUTE_IAT_TIME = "iat";
const ATTRIBUTE_SUBJECT = "sub";
const ATTRIBUTE_NAME = "name";
const ATTRIBUTE_PREFERRED_USERNAME = "preferred_username";
const ATTRIBUTE_EMAIL = "email";
const ATTRIBUTE_PHONE_NUMBER = "phone_number";

# 生成token的相关属性值
const TOKEN_EXPIRE_SECOND = 300;

const DEFAULT_TOKEN_PARAM = "id_token";
const APP_TYPE_PARAM = "{app_type}";

const App_Tencent_Meeting = "meeting";
const App_Tencent_Docs = "doc";

const DEFAULT_ALGORITHM = "RS256";