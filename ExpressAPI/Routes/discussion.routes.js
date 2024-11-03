const express = require('express');
const router = express.Router();
const discussionController = require('../Controllers/discussion.controller')

// ROUTES EN POST
router.post('/create', discussionController.createDiscussion);
router.post('/getAllMsgOfADiscussion', discussionController.getAllMsgOfADiscussion);
router.post('/createMsgWithinDiscussion', discussionController.createMsgWithinDiscussion);

// ROUTES EN GET
router.get('/getAll', discussionController.getAllDiscussions);
router.get('/getAllDiscussionsOfAUser/:id', discussionController.getDiscussionsOfAUser);
router.get('/getDiscussionById/:id', discussionController.getDiscussionById);

// ROUTES EN PATCH
router.patch('/patchDiscussion/:id', discussionController.patchDiscussion);

// ROUTES EN DELETE ( à faire une fois que j'aurai créer la 3ème API pour connecter les 2 autres)
// router.delete('/deleteDiscussion/:id', discussionController.deleteDiscussion);

module.exports = router;