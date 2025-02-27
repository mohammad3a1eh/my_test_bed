const express = require('express')
const app = express();
app.use(express.json());

const users = [
    { id: 100, Fname: "Victim", Lname: "User"},
    { id: 1, Fname: "Attacker", Lname: "User"}
];

app.use((req, res, next) => {
    req.user = { id:1 };
    next();
});

app.post('/editProfile', (req, res) => {
    const inputId = req.body.id;
    const userExists = users.some(user => user.id == inputId);
    if (userExists) {
        if (req.user.id != inputld) {
            return res.status(403).send("Forbidden: You cannot edit this profile.");
        }
    }


    const user = users.find(user => user.id == parseInt(inputId));
    if (!user) {
        return res.status(404).send("User not found.");
    }

    user.Fname = req.body.Fname || user.Fname;
    user.Lname = req.body.Lname || user.Lname;

    return res.send(`Profile updated for user id ${user.id}.\n ${user.Fname} , ${user.Lname}`);
});

app.listen(3000, () => console.log("Server running on port 3000"));

