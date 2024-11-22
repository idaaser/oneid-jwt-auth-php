<?php
//namespace oneid\jwt_auth;

# 生成token的属性名称
const CLAIM_EXPIRY = "exp";
const CLAIM_ISSUER = "iss";
const CLAIM_ISSUE_AT = "iat";
const CLAIM_SUBJECT = "sub";
const CLAIM_NAME = "name";
const CLAIM_PREFERRED_USERNAME = "preferred_username";
const CLAIM_EMAIL = "email";
const CLAIM_PHONE_NUMBER = "phone_number";
const CLAIM_AUDIENCE = "aud";
const CLAIM_JWT_ID = "jti";

# 生成token的相关属性值
const TOKEN_EXPIRE_SECOND = 300;

const DEFAULT_TOKEN_KEY = "id_token";
const APP_TYPE_PARAM = "{app_type}";

const App_Tencent_Meeting = "meeting";
const App_Tencent_Docs = "doc";
const App_Tencent_OneID = "sso_api";

const DEFAULT_ALGORITHM = "RS256";