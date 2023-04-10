from fastapi import FastAPI, HTTPException, Body, Request
from pymongo import MongoClient
import jwt
from typing import Any, Optional

# Set up the FastAPI app
app = FastAPI()

# Set up the MongoDB client
client = MongoClient('mongodb://mongo:27017/')
db = client['mydatabase']
users_collection = db['users']

# Set up JWT authentication
JWT_SECRET = "mustangs"
JWT_ALGORITHM = "HS256"

# Define the register endpoint
@app.post('/register')
async def register(username: str = Body(...), password: str = Body(...), role: Optional[str] = Body("user")):
    # Check if user already exists
    if users_collection.find_one({'username': username}):
        raise HTTPException(status_code=400, detail='User already exists')
    # Create user
    user = {'username': username, 'password': password, 'role': role}
    users_collection.insert_one(user)
    return {'message': 'User created successfully'}

# Define the login endpoint
@app.post('/login')
async def login(username: Any = Body(...), password: Any = Body(...)):
    # Check if user exists and password is correct
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
        jwt.decode(auth, "mustangs", algorithms=["HS256"])
    except jwt.exceptions.InvalidTokenError:
        raise HTTPException(status_code=401, detail="Invalid token")
    
    query = {"username": username}
    user = users_collection.find_one(query, projection={"_id": 0, "user_id": 0})
    if user is None:
        raise HTTPException(status_code=404, detail="User not found")
    return user


if __name__ == '__main__':
    import uvicorn
    uvicorn.run(app, host='0.0.0.0', port=8000)
