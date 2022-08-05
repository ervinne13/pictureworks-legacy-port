# Developer Notes

## "Deviations" From the Requirements

### Issue: Bad DB Design
The original application had the comments and user combined. This resulted in it updating a "comments" column that contains the user comments separated by new lines.

In our implementation, we'll write it properly with separate tables since the requirement just stated that we need the same output for an input, it didn't really tell us that we retain this practice.

### Issue: Passwords on Request Body

I really don't like that we are passing passwords on every request. But since the docs asked for 1:1 input and output, let's do this for now but keep in mind that this is not the way to do it.

If an application is calling this API and not via a session, then it's best to use something like mutual authentication instead. I've written my R&D results about this [here](https://github.com/ervinne13/mutual-authentication-protected-server).

Let's budget max 6hrs for this ask, if we still have time, then let's do them a demo on this, otherwise, just mention the concern on the interview.

## Frontend

Okay, I was overwhelmed here. It's been a while since I last touched Laravel and the frontend is very different now. Let's stick to blade for now then ask the employer whether the Senior Laravel Developer role is fullstack.