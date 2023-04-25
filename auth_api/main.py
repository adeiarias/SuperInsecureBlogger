from fastapi import FastAPI, HTTPException, Body, Request
from pymongo import MongoClient
import jwt
import os
import hashlib
from typing import Any, Optional

# Set up the FastAPI app
app = FastAPI()

# Set up the MongoDB client
mongo_uri = os.environ.get('MONGO_URI')
client = MongoClient(mongo_uri)
db = client['auth']
users_collection = db['users']

# Set up JWT authentication
JWT_SECRET = os.environ.get('JWT_SECRET')
JWT_ALGORITHM = "HS256"


@app.post('/register')
async def register(email: str = Body(...), username: str = Body(...), password: str = Body(...), role: Optional[str] = Body("user")):
    # Check if user already exists
    if users_collection.find_one({'username': username}):
        raise HTTPException(status_code=400, detail='User already exists')
    # Create user
    sha1 = hashlib.sha1()
    sha1.update(password.encode('utf-8'))
    password = sha1.hexdigest()
    user = {'username': username, 'password': password, 'email': email, 'role': role, 'photo': 'default.png'}
    users_collection.insert_one(user)
    return {'message': 'User created successfully'}


# Define the login endpoint
@app.post('/login')
async def login(username: Any = Body(...), password: Any = Body(...)):
    # Check if user exists and password is correct
    sha1 = hashlib.sha1()
    sha1.update(password.encode('utf-8'))
    password = sha1.hexdigest()
    
    query = {}
    query["username"] = username
    query["password"] = password
    user = users_collection.find_one(query)
    if not user:
        raise HTTPException(status_code=401, detail='Invalid username or password')
    # Generate JWT token
    payload = {
        'username': str(user['username']),
        'role': str(user["role"])
    }
    jwt_token = jwt.encode(payload, JWT_SECRET, algorithm=JWT_ALGORITHM)
    return {'token': jwt_token}


@app.get('/users/{username}')
async def get_user(username: Any, request: Request):
    auth = request.headers.get("auth")
    try:
        jwt.decode(auth, JWT_SECRET, algorithms=["HS256"])
    except jwt.exceptions.InvalidTokenError:
        raise HTTPException(status_code=401, detail="Invalid token")
    
    query = {}
    query["username"] = username
    user = users_collection.find_one(query, projection={"_id": 0, "user_id": 0})
    print(user)
    if user is None:
        raise HTTPException(status_code=404, detail="User not found")
    return user


@app.put('/user/email')
async def update_email(request: Request, username: Any = Body(...), email: Any = Body(...)):
    auth = request.headers.get("auth")
    try:
        jwt.decode(auth, JWT_SECRET, algorithms=["HS256"])
    except jwt.exceptions.InvalidTokenError:
        raise HTTPException(status_code=401, detail="Invalid token")
    
    # Check if the user exists
    user = users_collection.find_one({'username': username})
    if not user:
        raise HTTPException(status_code=400, detail="Invalid request")

    print(email)
    # Update the user's email
    users_collection.update_one({'username': username}, {'$set': {'email': email}})
    return {'message': 'Email updated successfully'}


@app.get('/user/photo')
async def get_user_photo(request: Request):
    auth = request.headers.get("auth")
    try:
        token = jwt.decode(auth, JWT_SECRET, algorithms=["HS256"])
    except jwt.exceptions.InvalidTokenError:
        raise HTTPException(status_code=401, detail="Invalid token")

    username = token.get("username")    
    query = {"username": username}
    user = users_collection.find_one(query, projection={"_id": 0, "user_id": 0})
    photo = user.get("photo")    
    return photo


@app.put('/user/newphoto')
async def update_user_photo(request: Request, photo: Any = Body(...)):
    auth = request.headers.get("auth")
    try:
        token = jwt.decode(auth, JWT_SECRET, algorithms=["HS256"])
    except jwt.exceptions.InvalidTokenError:
        raise HTTPException(status_code=401, detail="Invalid token")
    
    username = token.get("username")
    users_collection.update_one({'username': username}, {'$set': {'photo': photo["photo"]}})


if __name__ == '__main__':
    import uvicorn
    uvicorn.run(app, host='0.0.0.0', port=8000)